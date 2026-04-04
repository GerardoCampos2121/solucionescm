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
    <title>Complete Booking - Car Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap/5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <a href="car_details.php?id=<?php echo $carId; ?>" class="btn btn-secondary mb-4">← Back to Car Details</a>

    <?php if (isset($bookingConfirmed) && $bookingConfirmed): ?>
        <!-- Success Message -->
        <div class="alert alert-success shadow" role="alert">
            <h4 class="alert-heading">🎉 Booking Confirmed!</h4>
            <p>Your booking for the <?php echo $selectedCar['make'] . ' ' . $selectedCar['model']; ?> has been successfully confirmed.</p>
            <hr>
            <p class="mb-0">A confirmation email will be sent to <?php echo htmlspecialchars($customerData['email']); ?></p>
        </div>
        
        <div class="card shadow mt-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Booking Summary</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Vehicle Details</h6>
                        <p><strong>Car:</strong> <?php echo $selectedCar['make'] . ' ' . $selectedCar['model']; ?></p>
                        <p><strong>Rental Period:</strong> <?php echo $startDate; ?> to <?php echo $endDate; ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6>Customer Information</h6>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($customerData['name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($customerData['email']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($customerData['phone']); ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-primary btn-lg">Return to Dashboard</a>
        </div>
    <?php else: ?>
        <!-- Booking Form -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Complete Your Booking</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="booking_form.php?car_id=<?php echo $carId; ?>&start_date=<?php echo urlencode($startDate); ?>&end_date=<?php echo urlencode($endDate); ?>">
                            <!-- Hidden fields for car and dates -->
                            <input type="hidden" name="car_id" value="<?php echo $carId; ?>">
                            <input type="hidden" name="start_date" value="<?php echo $startDate; ?>">
                            <input type="hidden" name="end_date" value="<?php echo $endDate; ?>">

                            <h5 class="mb-3">Personal Information</h5>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required 
                                       placeholder="Enter your full name">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="document_id" class="form-label">Document ID *</label>
                                    <input type="text" class="form-control" id="document_id" name="document_id" required 
                                           placeholder="DUI, Passport, etc.">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="age" class="form-label">Age *</label>
                                    <input type="number" class="form-control" id="age" name="age" required 
                                           min="18" max="100" placeholder="Must be 18+">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address *</label>
                                <textarea class="form-control" id="address" name="address" rows="2" required 
                                          placeholder="Enter your full address"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="email" name="email" required 
                                           placeholder="your@email.com">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required 
                                           placeholder="+503 XXXX-XXXX">
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    Confirm Booking
                                </button>
                                <a href="car_details.php?id=<?php echo $carId; ?>" class="btn btn-outline-secondary">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Booking Summary Card -->
                <div class="card shadow sticky-top" style="top: 20px;">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Booking Summary</h5>
                    </div>
                    <div class="card-body">
                        <img src="<?php echo $selectedCar['image']; ?>" 
                             class="img-fluid rounded mb-3" 
                             alt="<?php echo $selectedCar['make'] . ' ' . $selectedCar['model']; ?>"
                             style="height: 200px; object-fit: cover; width: 100%;">
                        
                        <h5><?php echo $selectedCar['make'] . ' ' . $selectedCar['model']; ?></h5>
                        
                        <hr>
                        
                        <div class="mb-3">
                            <small class="text-muted">Rental Period</small>
                            <p class="mb-1"><strong>From:</strong> <?php echo date('M d, Y', strtotime($startDate)); ?></p>
                            <p class="mb-0"><strong>To:</strong> <?php echo date('M d, Y', strtotime($endDate)); ?></p>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">Duration</small>
                            <p class="mb-0">
                                <?php 
                                $days = (strtotime($endDate) - strtotime($startDate)) / 86400;
                                echo "<strong>" . $days . " day(s)</strong>";
                                ?>
                            </p>
                        </div>

                        <div class="alert alert-info">
                            <small>
                                <strong>Note:</strong> Please ensure all information is accurate. 
                                You must be at least 18 years old to rent a vehicle.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>