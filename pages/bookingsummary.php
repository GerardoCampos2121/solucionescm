<?php
session_start();

// Get booking data from session (transferred via POST from booking_form.php)
$bookingData = $_SESSION['booking_data'] ?? null;

// Validate that we have booking data
if (!$bookingData) {
    die("No booking information found. Please start a new booking from the beginning.");
}

// Extract booking data
$carId = $bookingData['car_id'] ?? null;
$startDate = $bookingData['start_date'] ?? null;
$endDate = $bookingData['end_date'] ?? null;

// Customer data from session
$customerName = $bookingData['name'] ?? 'N/A';
$documentId = $bookingData['document_id'] ?? 'N/A';
$customerAge = $bookingData['age'] ?? 'N/A';
$address = $bookingData['address'] ?? 'N/A';
$email = $bookingData['email'] ?? 'N/A';
$phone = $bookingData['phone'] ?? 'N/A';


// Car data (same as in other files - later this will come from database)
$cars = [
    [
        'id' => 1,
        'make' => 'Toyota',
        'model' => 'Corolla',
        'status' => 'Available',
        'price_per_day' => 45.00,
        'image' => 'https://images.unsplash.com/photo-1549924231-f129b911e442?auto=format&fit=crop&w=800&q=60'
    ],
    [
        'id' => 2,
        'make' => 'Honda',
        'model' => 'Civic',
        'status' => 'Rented',
        'price_per_day' => 50.00,
        'image' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=800&q=60'
    ],
    [
        'id' => 3,
        'make' => 'Ford',
        'model' => 'Mustang',
        'status' => 'Available',
        'price_per_day' => 75.00,
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

// Calculate rental details
$startTime = strtotime($startDate);
$endTime = strtotime($endDate);
$days = ($endTime - $startTime) / 86400;
$pricePerDay = $selectedCar['price_per_day'];
$subtotal = $days * $pricePerDay;
$tax = $subtotal * 0.13; // 13% tax
$total = $subtotal + $tax;

// Generate booking ID
$bookingId = 'BK-' . strtoupper(substr(uniqid(), -6));
$bookingDate = date('Y-m-d H:i:s');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Summary - SECM Rent a Car</title>
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap/5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap/5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        
        .summary-container {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
        }
        
        .booking-header {
            text-align: center;
            padding: 30px 20px;
            background: linear-gradient(135deg, #111827, #1f2937);
            color: white;
            border-radius: 10px 10px 0 0;
            margin-bottom: 0;
        }
        
        .booking-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .booking-header .success-icon {
            font-size: 3rem;
            color: #10b981;
            margin-bottom: 15px;
        }
        
        .booking-id-badge {
            display: inline-block;
            background: rgba(255,255,255,0.15);
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 1.1rem;
            font-weight: 500;
        }
        
        .summary-card {
            background: white;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1f2937;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 20px;
        }
        
        .section-title i {
            margin-right: 8px;
            color: #3b82f6;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            color: #6b7280;
            font-weight: 500;
        }
        
        .info-value {
            color: #1f2937;
            font-weight: 600;
            text-align: right;
            padding-right: 50px;
        }
        
        .car-summary-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        
        .price-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        
        .price-row.total {
            border-top: 2px solid #86efac;
            margin-top: 10px;
            padding-top: 15px;
            font-size: 1.3rem;
            font-weight: 700;
            color: #166534;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            justify-content: center;
        }
        
        .btn-print {
            background: #3b82f6;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-print:hover {
            background: #2563eb;
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-home {
            background: #6b7280;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-home:hover {
            background: #4b5563;
            color: white;
            transform: translateY(-2px);
        }
        
        .printable-area {
            padding: 20px;
        }
        
        .print-header {
            display: none;
            text-align: center;
            margin-bottom: 20px;
        }
        
        @media print {
            body * {
                visibility: hidden;
            }
            
            .printable-area, .printable-area * {
                visibility: visible;
            }
            
            .printable-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            
            .action-buttons, .navbar {
                display: none !important;
            }
            
            .print-header {
                display: block;
            }
            
            .booking-header {
                background: #1f2937 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
        
        .status-badge {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .status-confirmed {
            background: #d1fae5;
            color: #065f46;
        }
    </style>
</head>
<body>

<?php include("topmenu.php"); ?>

<div class="container summary-container">
    <!-- Printable Area -->
    <div class="printable-area" id="printableArea">
        
        <!-- Print-only header -->
        <div class="print-header">
            <h2>SECM Rent a Car</h2>
            <p>Booking Confirmation Receipt</p>
            <hr>
        </div>
        
        <!-- Booking Header -->
        <div class="booking-header">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1>Booking Confirmed!</h1>
            <p class="mb-3">Your reservation has been successfully created</p>
            <span class="booking-id-badge">
                <i class="fas fa-hashtag me-1"></i>Booking ID: <?php echo $bookingId; ?>
            </span>
        </div>
        
        <div class="summary-card">
            <div class="p-4">
                
                <!-- Car Information Section -->
                <div class="section-title">
                    <i class="fas fa-car"></i>Vehicle Information
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <img src="<?php echo $selectedCar['image']; ?>" 
                             alt="<?php echo $selectedCar['make'] . ' ' . $selectedCar['model']; ?>" 
                             class="car-summary-img">
                    </div>
                    <div class="col-md-7">
                        <h4 class="mb-3"><?php echo $selectedCar['make'] . ' ' . $selectedCar['model']; ?></h4>
                        <span class="status-badge status-confirmed">
                            <i class="fas fa-check-circle me-1"></i>Confirmed
                        </span>
                        <div class="mt-3">
                            <div class="info-row">
                                <span class="info-label">Vehicle ID</span>
                                <span class="info-value">#<?php echo $selectedCar['id']; ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Category</span>
                                <span class="info-value">Standard</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Price/Day</span>
                                <span class="info-value">$<?php echo number_format($pricePerDay, 2); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <!-- Rental Period Section -->
                <div class="section-title">
                    <i class="fas fa-calendar-alt"></i>Rental Period
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-row">
                            <span class="info-label">Start Date</span>
                            <span class="info-value"><?php echo date('M d, Y', $startTime); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Start Time</span>
                            <span class="info-value">09:00 AM</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <span class="info-label">End Date</span>
                            <span class="info-value"><?php echo date('M d, Y', $endTime); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">End Time</span>
                            <span class="info-value">09:00 AM</span>
                        </div>
                    </div>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-clock me-2 text-primary"></i>Duration</span>
                    <span class="info-value"><?php echo $days; ?> day(s)</span>
                </div>
                
                <hr class="my-4">
                
                <!-- Customer Information Section -->
                <div class="section-title">
                    <i class="fas fa-user-circle"></i>Customer Information
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-row">
                            <span class="info-label">Full Name</span>
                            <span class="info-value"><?php echo htmlspecialchars($customerName); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Document ID</span>
                            <span class="info-value"><?php echo htmlspecialchars($documentId); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Age</span>
                            <span class="info-value"><?php echo htmlspecialchars($customerAge); ?> years</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <span class="info-label">Email</span>
                            <span class="info-value"><?php echo htmlspecialchars($email); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Phone</span>
                            <span class="info-value"><?php echo htmlspecialchars($phone); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Address</span>
                            <span class="info-value" style="max-width: 200px;"><?php echo htmlspecialchars($address); ?></span>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <!-- Booking Details Section -->
                <div class="section-title">
                    <i class="fas fa-receipt"></i>Booking Details
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-row">
                            <span class="info-label">Booking ID</span>
                            <span class="info-value"><?php echo $bookingId; ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Booking Date</span>
                            <span class="info-value"><?php echo $bookingDate; ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <span class="info-label">Payment Status</span>
                            <span class="info-value"><span class="status-badge status-confirmed">Pending</span></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Pickup Location</span>
                            <span class="info-value">Main Office</span>
                        </div>
                    </div>
                </div>
                
                <!-- Price Summary -->
                <div class="price-box">
                    <div class="section-title" style="border-bottom: none; margin-bottom: 15px;">
                        <i class="fas fa-dollar-sign"></i>Price Summary
                    </div>
                    <div class="price-row">
                        <span><?php echo $days; ?> day(s) × $<?php echo number_format($pricePerDay, 2); ?>/day</span>
                        <span>$<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="price-row">
                        <span>Tax (13%)</span>
                        <span>$<?php echo number_format($tax, 2); ?></span>
                    </div>
                    <div class="price-row total">
                        <span>Total Amount</span>
                        <span>$<?php echo number_format($total, 2); ?></span>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="action-buttons">
        <button class="btn-print" onclick="printBooking()">
            <i class="fas fa-print me-2"></i>Print Reservation
        </button>
        <a href="../index.php" class="btn-home">
            <i class="fas fa-home me-2"></i>Back to Home
        </a>
    </div>
    
</div>

<!-- Footer -->
<footer class="mt-5 py-3" style="background: #1f2937; color: white;">
    <div class="container text-center">
        <p class="mb-0">
            <i class="fas fa-car me-2"></i>&copy; 2026 SECM Rent a Car. All rights reserved.
        </p>
    </div>
</footer>

<script>
    function printBooking() {
        window.print();
    }
</script>

</body>
</html>