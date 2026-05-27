<?php
session_start();

// Get car ID and dates from URL parameters
$carId = $_GET['car_id'] ?? null;
$startDate = $_GET['start_date'] ?? null;
$endDate = $_GET['end_date'] ?? null;
$bookingConfirmed = false;

// Car data (same as in car_details.php - later this will come from database)
$cars = [
    [
        'id' => 1,
        'make' => 'Toyota',
        'model' => 'Corolla',
        'status' => 'Available',
        'image' => 'https://images.unsplash.com/photo-1549924231-f129b911e442?auto=format&fit=crop&w=800&q=60'
    ],
    [
        'id' => 2,
        'make' => 'Honda',
        'model' => 'Civic',
        'status' => 'Rented',
        'image' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=800&q=60'
    ],
    [
        'id' => 3,
        'make' => 'Ford',
        'model' => 'Mustang',
        'status' => 'Available',
        'image' => 'images/forte2014.jpeg'
    ],
];

// Find selected car
$selectedCar = null;

foreach ($cars as $car) {
    if ($car['id'] == $carId) {
        $selectedCar = $car;
        break;
    }
}

// If car not found or no dates selected
if (!$selectedCar || !$startDate || !$endDate) {
    die("Invalid booking information. Please go back and select a car and dates.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $customerData = [
        'car_id' => $_POST['car_id'] ?? '',
        'start_date' => $_POST['start_date'] ?? '',
        'end_date' => $_POST['end_date'] ?? '',
        'name' => $_POST['name'] ?? '',
        'document_id' => $_POST['document_id'] ?? '',
        'age' => $_POST['age'] ?? '',
        'address' => $_POST['address'] ?? '',
        'email' => $_POST['email'] ?? '',
        'phone' => $_POST['phone'] ?? ''
    ];
    
    // Store booking data in session for transfer to summary page
    $_SESSION['booking_data'] = $customerData;
    
    // Generate booking ID for the email
    $bookingId = 'BK-' . strtoupper(substr(uniqid(), -6));
    $bookingDate = date('Y-m-d H:i:s');
    
    // Calculate rental details for email
    $startTime = strtotime($customerData['start_date']);
    $endTime = strtotime($customerData['end_date']);
    $days = ($endTime - $startTime) / 86400;
    
    // Get car price
    $pricePerDay = 0;
    foreach ($cars as $car) {
        if ($car['id'] == $customerData['car_id']) {
            $pricePerDay = $car['price_per_day'] ?? 0;
            break;
        }
    }
    
    $subtotal = $days * $pricePerDay;
    $tax = $subtotal * 0.13;
    $total = $subtotal + $tax;
    
    // Send email with booking summary
    $to = 'technoclick25@gmail.com';
    $subject = 'New Booking Confirmation - ' . $bookingId;
    
    // Build HTML email content
    $message = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .header { background: linear-gradient(135deg, #111827, #1f2937); color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; }
            .section { margin-bottom: 20px; }
            .section h3 { color: #1f2937; border-bottom: 2px solid #e5e7eb; padding-bottom: 5px; }
            .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3f4f6; }
            .label { font-weight: bold; color: #6b7280; }
            .value { color: #1f2937; }
            .price-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 15px; margin-top: 15px; }
            .total { font-size: 1.2em; font-weight: bold; color: #166534; border-top: 2px solid #86efac; padding-top: 10px; margin-top: 10px; }
            .footer { text-align: center; padding: 15px; background: #f8f9fa; font-size: 0.9em; color: #6b7280; }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>🚗 SECM Rent a Car</h1>
            <h2>Booking Confirmation</h2>
            <p>Booking ID: <strong>' . $bookingId . '</strong></p>
        </div>
        
        <div class="content">
            <div class="section">
                <h3>📋 Vehicle Information</h3>
                <div class="info-row">
                    <span class="label">Vehicle:</span>
                    <span class="value">' . $selectedCar['make'] . ' ' . $selectedCar['model'] . '</span>
                </div>
                <div class="info-row">
                    <span class="label">Price per Day:</span>
                    <span class="value">$' . number_format($pricePerDay, 2) . '</span>
                </div>
            </div>
            
            <div class="section">
                <h3>📅 Rental Period</h3>
                <div class="info-row">
                    <span class="label">Start Date:</span>
                    <span class="value">' . date('M d, Y', $startTime) . '</span>
                </div>
                <div class="info-row">
                    <span class="label">End Date:</span>
                    <span class="value">' . date('M d, Y', $endTime) . '</span>
                </div>
                <div class="info-row">
                    <span class="label">Duration:</span>
                    <span class="value">' . $days . ' day(s)</span>
                </div>
            </div>
            
            <div class="section">
                <h3>👤 Customer Information</h3>
                <div class="info-row">
                    <span class="label">Name:</span>
                    <span class="value">' . htmlspecialchars($customerData['name']) . '</span>
                </div>
                <div class="info-row">
                    <span class="label">Document ID:</span>
                    <span class="value">' . htmlspecialchars($customerData['document_id']) . '</span>
                </div>
                <div class="info-row">
                    <span class="label">Age:</span>
                    <span class="value">' . htmlspecialchars($customerData['age']) . ' years</span>
                </div>
                <div class="info-row">
                    <span class="label">Email:</span>
                    <span class="value">' . htmlspecialchars($customerData['email']) . '</span>
                </div>
                <div class="info-row">
                    <span class="label">Phone:</span>
                    <span class="value">' . htmlspecialchars($customerData['phone']) . '</span>
                </div>
                <div class="info-row">
                    <span class="label">Address:</span>
                    <span class="value">' . htmlspecialchars($customerData['address']) . '</span>
                </div>
            </div>
            
            <div class="section">
                <h3>💰 Price Summary</h3>
                <div class="price-box">
                    <div class="info-row">
                        <span class="label">' . $days . ' day(s) × $' . number_format($pricePerDay, 2) . '/day</span>
                        <span class="value">$' . number_format($subtotal, 2) . '</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Tax (13%):</span>
                        <span class="value">$' . number_format($tax, 2) . '</span>
                    </div>
                    <div class="total">
                        <div class="info-row">
                            <span class="label">Total Amount:</span>
                            <span class="value">$' . number_format($total, 2) . '</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="section">
                <h3>📝 Booking Details</h3>
                <div class="info-row">
                    <span class="label">Booking ID:</span>
                    <span class="value">' . $bookingId . '</span>
                </div>
                <div class="info-row">
                    <span class="label">Booking Date:</span>
                    <span class="value">' . $bookingDate . '</span>
                </div>
                <div class="info-row">
                    <span class="label">Payment Status:</span>
                    <span class="value">Pending</span>
                </div>
                <div class="info-row">
                    <span class="label">Pickup Location:</span>
                    <span class="value">Main Office</span>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>SECM Rent a Car</strong></p>
            <p>Email: support@secmrentacar.com</p>
            <p>This is an automated message. Please do not reply directly to this email.</p>
        </div>
    </body>
    </html>';
    
    // Set email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: SECM Rent a Car <noreply@secmrentacar.com>" . "\r\n";
    $headers .= "Reply-To: support@secmrentacar.com" . "\r\n";
    
    // Send email
    mail($to, $subject, $message, $headers);
    
    // Redirect to summary page (data will be retrieved from session)
    header('Location: pages/bookingsummary.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("pages/header.php");?>
</head>
<body>

<?php include("pages/topmenu.php");?>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="back-btn mb-3 d-inline-block" href="index.php">
                <i class="fas fa-arrow-left me-2"></i>Ir a inicio
            </a>          
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <a href="car_details.php?id=<?php echo $carId; ?>" class="back-btn mb-3 d-inline-block">
                <i class="fas fa-arrow-left me-2"></i>Back to Car Details
            </a>
            <h1><i class="fas fa-calendar-check me-2"></i>Complete Your Booking</h1>
            <p class="mb-0">Provide your information to finalize your car rental reservation</p>
        </div>
    </div>
            

        <div class="checkout-grid d-flex justify-content-center align-items-center vh-400">
            <div class="form-container">
                <!-- Booking Form - Three Column Layout -->
                <form method="POST" action="booking_form.php?car_id=<?php echo $carId; ?>&start_date=<?php echo urlencode($startDate); ?>&end_date=<?php echo urlencode($endDate); ?>">
                    <!-- Hidden fields for car and dates -->
                    <input type="hidden" name="car_id" value="<?php echo $carId; ?>">
                    <input type="hidden" name="start_date" value="<?php echo $startDate; ?>">
                    <input type="hidden" name="end_date" value="<?php echo $endDate; ?>">
                    
                    <div class="row g-2">
                        <!-- Column 1: Primary Details -->
                        <div class="col-lg-4">
                            <div class="booking-card h-100">
                                <div class="card-header-custom">
                                    <h4><i class="fas fa-id-card me-2"></i>Primary Details</h4>
                                </div>
                                <div class="card-body p-4">
                                    <div class="checkout-form">
                                        <label for="name" class="form-label">
                                            <i class="fas fa-user me-1 text-primary"></i>Full Name <span class="required">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="name" name="name" required 
                                                placeholder="Your full legal name">                                        
                                        <div class="form-text">As it appears on your ID</div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="document_id" class="form-label">
                                            <i class="fas fa-passport me-1 text-primary"></i>Document ID <span class="text-danger">*</span>
                                        </label>                                        
                                        <input type="text" class="form-control" id="document_id" name="document_id" required 
                                            placeholder="DUI, Passport, etc.">                                        
                                        <div class="form-text">Valid government-issued ID</div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="age" class="form-label">
                                            <i class="fas fa-birthday-cake me-1 text-primary"></i>Age <span class="text-danger">*</span>
                                        </label>                                        
                                        <input type="number" class="form-control" id="age" name="age" required 
                                                min="18" max="100" placeholder="18+">                                        
                                        <div class="form-text">Minimum 18 years required</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Column 2: Contact Information -->
                        <div class="col-lg-4">
                            <div class="booking-card h-100">
                                <div class="card-header-custom">
                                    <h4><i class="fas fa-address-book me-2"></i>Contact Information</h4>
                                </div>
                                <div class="card-body p-4">
                                    <div class="mb-4">
                                        <label for="address" class="form-label">
                                            <i class="fas fa-home me-1 text-primary"></i>Full Address <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-home"></i>
                                            </span>
                                            <textarea class="form-control" id="address" name="address" rows="3" required 
                                                    placeholder="Your complete residential address"></textarea>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="email" class="form-label">
                                            <i class="fas fa-envelope me-1 text-primary"></i>Email Address <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-envelope"></i>
                                            </span>
                                            <input type="email" class="form-control" id="email" name="email" required 
                                                placeholder="your@email.com">
                                        </div>
                                        <div class="form-text">Confirmation sent here</div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="phone" class="form-label">
                                            <i class="fas fa-phone me-1 text-primary"></i>Phone Number <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-phone"></i>
                                            </span>
                                            <input type="tel" class="form-control" id="phone" name="phone" required 
                                                placeholder="+503 XXXX-XXXX">
                                        </div>
                                        <div class="form-text">For booking updates</div>
                                    </div>

                                    <div class="info-box mt-4">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Important:</strong> All information must be accurate for verification purposes.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Column 3: Booking Summary -->
                        <div class="col-lg-4">
                            <div class="booking-card h-100">
                                <div class="card-header-custom">
                                    <h4><i class="fas fa-receipt me-2"></i>Booking Summary</h4>
                                </div>
                                <div class="card-body p-4">
                                    <div class="text-center mb-3">
                                        <img src="<?php echo $selectedCar['image']; ?>" 
                                             alt="<?php echo $selectedCar['make'] . ' ' . $selectedCar['model']; ?>" 
                                             style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">
                                    </div>
                                    <h5 class="text-center mb-3"><?php echo $selectedCar['make'] . ' ' . $selectedCar['model']; ?></h5>
                                    
                                    <div class="info-row">
                                        <span class="info-label">Start Date</span>
                                        <span class="info-value"><?php echo date('M d, Y', strtotime($startDate)); ?></span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">End Date</span>
                                        <span class="info-value"><?php echo date('M d, Y', strtotime($endDate)); ?></span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Duration</span>
                                        <span class="info-value">
                                            <?php
                                            $days = (strtotime($endDate) - strtotime($startDate)) / 86400;
                                            echo $days . ' day(s)';
                                            ?>
                                        </span>
                                    </div>
                                    
                                    <div class="info-box mt-4">
                                        <i class="fas fa-shield-alt me-2"></i>
                                        <strong>Secure Booking</strong><br>
                                        <small>Your information is protected.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-3 mt-4">
                <button type="submit" class="btn-confirm">
                    <i class="fas fa-check-circle me-2"></i>Confirm Booking
                </button>
                        <a href="car_details.php?id=<?php echo $carId; ?>" class="btn btn-cancel">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                    </form>

                </div>
            </div>
        </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p class="mb-0">
                <i class="fas fa-car me-2"></i>&copy; 2026 SECM Rent a Car. All rights reserved.
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap/5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>