<?php
// Get car ID and dates from URL parameters
$carId = $_GET['car_id'] ?? null;
$startDate = $_GET['start_date'] ?? null;
$endDate = $_GET['end_date'] ?? null;

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
    
    // Here you would typically save to database
    // For now, we'll just show a success message
    $bookingConfirmed = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Booking - SECM Rent a Car</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap/5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #1e3a8a;
            --secondary-color: #3b82f6;
            --accent-color: #10b981;
            --dark-bg: #0f172a;
            --light-bg: #f8fafc;
            --card-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 100%);
            min-height: 100vh;
            padding-bottom: 60px;
        }

        .navbar {
            background: linear-gradient(90deg, #1e3a8a, #3b82f6) !important;
            box-shadow: 0 4px 20px rgba(30, 58, 138, 0.2);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
        }

        .page-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 30px 30px;
            box-shadow: 0 10px 30px rgba(30, 58, 138, 0.3);
        }

        .page-header h1 {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .page-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 500;
            transition: var(--transition);
            backdrop-filter: blur(10px);
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            color: white;
        }

        .booking-card {
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            border: none;
            overflow: hidden;
            transition: var(--transition);
        }

        .booking-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
        }

        .card-header-custom {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            padding: 1.5rem 2rem;
            border: none;
        }

        .card-header-custom h4 {
            font-weight: 600;
            margin: 0;
        }

        .form-label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-control, .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .form-control:hover {
            border-color: #9ca3af;
        }

        .input-group-text {
            background: #f3f4f6;
            border: 2px solid #e5e7eb;
            border-right: none;
            border-radius: 12px 0 0 12px;
            color: #6b7280;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 12px 12px 0;
        }

        .input-group .form-control:focus {
            border-left-color: #3b82f6;
        }

        .summary-card {
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            border: none;
            position: sticky;
            top: 100px;
        }

        .summary-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
            padding: 1.25rem 1.5rem;
            border-radius: 20px 20px 0 0;
            font-weight: 600;
        }

        .car-image-container {
            position: relative;
            overflow: hidden;
            border-radius: 15px;
            margin-bottom: 1.5rem;
        }

        .car-image-container img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: var(--transition);
        }

        .car-image-container:hover img {
            transform: scale(1.05);
        }

        .car-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--accent-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-label {
            color: #6b7280;
            font-size: 0.9rem;
        }

        .summary-value {
            font-weight: 600;
            color: #1f2937;
        }

        .btn-confirm {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            width: 100%;
        }

        .btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }

        .btn-cancel {
            background: white;
            border: 2px solid #e5e7eb;
            color: #6b7280;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-cancel:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
            color: #4b5563;
        }

        .section-divider {
            border-top: 2px solid #e5e7eb;
            margin: 2rem 0;
            position: relative;
        }

        .section-divider::before {
            content: '';
            position: absolute;
            top: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 2px;
            background: linear-gradient(90deg, #1e3a8a, #3b82f6);
        }

        .alert-custom {
            border-radius: 12px;
            padding: 1rem 1.25rem;
            border: none;
            font-size: 0.95rem;
        }

        .success-card {
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            border: none;
            overflow: hidden;
        }

        .success-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .success-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            animation: bounce 0.6s ease;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .info-box {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-left: 4px solid #3b82f6;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-top: 1rem;
        }

        .info-box i {
            color: #3b82f6;
            margin-right: 0.5rem;
        }

        .form-section-title {
            color: #1e3a8a;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-section-title i {
            font-size: 1.2rem;
        }

        footer {
            background: var(--dark-bg);
            color: #9ca3af;
            padding: 2rem 0;
            margin-top: 4rem;
            text-align: center;
        }

        @media (max-width: 991px) {
            .summary-card {
                position: static;
                margin-top: 2rem;
            }
            
            .page-header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-car me-2"></i>SECM Rent a Car
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Dashboard</a>
                    </li>
                </ul>
            </div>
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

    <div class="container">
        <?php if (isset($bookingConfirmed) && $bookingConfirmed): ?>
            <!-- Success Message -->
            <div class="success-card mb-4">
                <div class="success-header">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h2 class="mb-2">Booking Confirmed!</h2>
                    <p class="mb-0">Your reservation has been successfully created</p>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3"><i class="fas fa-car me-2"></i>Vehicle Details</h5>
                            <div class="summary-item">
                                <span class="summary-label">Car Model</span>
                                <span class="summary-value"><?php echo $selectedCar['make'] . ' ' . $selectedCar['model']; ?></span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Rental Period</span>
                                <span class="summary-value"><?php echo date('M d, Y', strtotime($startDate)); ?> - <?php echo date('M d, Y', strtotime($endDate)); ?></span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Duration</span>
                                <span class="summary-value">
                                    <?php 
                                    $days = (strtotime($endDate) - strtotime($startDate)) / 86400;
                                    echo $days . ' day(s)';
                                    ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3"><i class="fas fa-user me-2"></i>Customer Information</h5>
                            <div class="summary-item">
                                <span class="summary-label">Name</span>
                                <span class="summary-value"><?php echo htmlspecialchars($customerData['name']); ?></span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Email</span>
                                <span class="summary-value"><?php echo htmlspecialchars($customerData['email']); ?></span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Phone</span>
                                <span class="summary-value"><?php echo htmlspecialchars($customerData['phone']); ?></span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Document ID</span>
                                <span class="summary-value"><?php echo htmlspecialchars($customerData['document_id']); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-box mt-4">
                        <i class="fas fa-envelope me-2"></i>
                        <strong>Confirmation Email:</strong> A detailed confirmation has been sent to <?php echo htmlspecialchars($customerData['email']); ?>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="index.php" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-home me-2"></i>Return to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Booking Form -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="booking-card">
                        <div class="card-header-custom">
                            <h4><i class="fas fa-user-circle me-2"></i>Personal Information</h4>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="booking_form.php?car_id=<?php echo $carId; ?>&start_date=<?php echo urlencode($startDate); ?>&end_date=<?php echo urlencode($endDate); ?>">
                                <!-- Hidden fields for car and dates -->
                                <input type="hidden" name="car_id" value="<?php echo $carId; ?>">
                                <input type="hidden" name="start_date" value="<?php echo $startDate; ?>">
                                <input type="hidden" name="end_date" value="<?php echo $endDate; ?>">

                                <div class="form-section-title">
                                    <i class="fas fa-id-card"></i>
                                    <span>Primary Details</span>
                                </div>

                                <div class="mb-4">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" class="form-control" id="name" name="name" required 
                                               placeholder="Enter your full legal name as it appears on your ID">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="document_id" class="form-label">Document ID <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-passport"></i>
                                            </span>
                                            <input type="text" class="form-control" id="document_id" name="document_id" required 
                                                   placeholder="DUI, Passport, etc.">
                                        </div>
                                        <div class="form-text">Must be a valid government-issued ID</div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label for="age" class="form-label">Age <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-birthday-cake"></i>
                                            </span>
                                            <input type="number" class="form-control" id="age" name="age" required 
                                                   min="18" max="100" placeholder="Must be 18+">
                                        </div>
                                        <div class="form-text">Minimum age requirement is 18 years</div>
                                    </div>
                                </div>

                                <div class="section-divider"></div>

                                <div class="form-section-title">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>Contact Information</span>
                                </div>

                                <div class="mb-4">
                                    <label for="address" class="form-label">Full Address <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-home"></i>
                                        </span>
                                        <textarea class="form-control" id="address" name="address" rows="2" required 
                                                  placeholder="Enter your complete residential address"></textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-envelope"></i>
                                            </span>
                                            <input type="email" class="form-control" id="email" name="email" required 
                                                   placeholder="your@email.com">
                                        </div>
                                        <div class="form-text">Confirmation will be sent here</div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-phone"></i>
                                            </span>
                                            <input type="tel" class="form-control" id="phone" name="phone" required 
                                                   placeholder="+503 XXXX-XXXX">
                                        </div>
                                        <div class="form-text">For booking updates and emergencies</div>
                                    </div>
                                </div>

                                <div class="section-divider"></div>

                                <div class="info-box">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Important:</strong> By submitting this form, you agree to our rental terms and conditions. 
                                    Please ensure all information is accurate as it will be used for verification purposes.
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

                <div class="col-lg-4">
                    <!-- Booking Summary Card -->
                    <div class="summary-card">
                        <div class="summary-header">
                            <i class="fas fa-receipt me-2"></i>Booking Summary
                        </div>
                        <div class="card-body p-4">
                            <div class="car-image-container">
                                <img src="<?php echo $selectedCar['image']; ?>" 
                                     alt="<?php echo $selectedCar['make'] . ' ' . $selectedCar['model']; ?>">
                                <span class="car-badge">
                                    <i class="fas fa-car me-1"></i><?php echo $selectedCar['status']; ?>
                                </span>
                            </div>
                            
                            <h4 class="mb-3"><?php echo $selectedCar['make'] . ' ' . $selectedCar['model']; ?></h4>
                            
                            <div class="summary-item">
                                <span class="summary-label">
                                    <i class="fas fa-calendar-alt me-2 text-primary"></i>Start Date
                                </span>
                                <span class="summary-value"><?php echo date('M d, Y', strtotime($startDate)); ?></span>
                            </div>
                            
                            <div class="summary-item">
                                <span class="summary-label">
                                    <i class="fas fa-calendar-check me-2 text-primary"></i>End Date
                                </span>
                                <span class="summary-value"><?php echo date('M d, Y', strtotime($endDate)); ?></span>
                            </div>
                            
                            <div class="summary-item">
                                <span class="summary-label">
                                    <i class="fas fa-clock me-2 text-primary"></i>Duration
                                </span>
                                <span class="summary-value">
                                    <?php 
                                    $days = (strtotime($endDate) - strtotime($startDate)) / 86400;
                                    echo $days . ' day(s)';
                                    ?>
                                </span>
                            </div>
                            
                            <div class="summary-item">
                                <span class="summary-label">
                                    <i class="fas fa-hashtag me-2 text-primary"></i>Booking ID
                                </span>
                                <span class="summary-value">#BK-<?php echo strtoupper(substr(uniqid(), -6)); ?></span>
                            </div>

                            <div class="alert alert-custom alert-info mt-4">
                                <i class="fas fa-shield-alt me-2"></i>
                                <strong>Secure Booking</strong><br>
                                <small>Your information is protected and will only be used for this reservation.</small>
                            </div>

                            <div class="info-box mt-3">
                                <i class="fas fa-question-circle me-2"></i>
                                <strong>Need Help?</strong><br>
                                <small>Contact us at support@secmrentacar.com</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
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