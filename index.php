<?php

include("conf/Service.php");

// Read vehicles from database using listaVehiculos function
$response = listaVehiculos();
$num_vehiculos = count($response);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SECM Rent a Car - Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    

    <!-- Bootstrap 5 CDN -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
     <!-- Bootstrap JS -->
    <script src="js/bootstrap.bundle.min.js"></script>
   
    <!-- Custom CSS -->
    <link href="css/alquilersecm.css" rel="stylesheet">

    <style>
        /* Page Background */
        body {
            background: white;
            background-attachment: fixed;
            min-height: 100vh;
            font-family: "Roboto", sans-serif;
        }

       .gallery-img { height: 200px; object-fit: cover; width: 100%; border-radius: 8px; }
    </style>
    
</head>
<body>

<!-- 🔷 HEADER / NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow"
     style="background: linear-gradient(90deg, #111827, #1f2937);">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">🚗 SECM Rent a Car</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Inicio</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- 🔷 MAIN CONTENT -->
<div class="container mt-5">
    

    <!-- Welcome Section -->
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold" style="color: #111827;">Welcome to SECM Rent a Car</h1>
        <p class="text-muted lead">Choose from our selection of quality vehicles</p>
    </div>

    <!-- Available Cars Grid -->
    <div class="mb-5">
        <h2>Available Vehicles</h2>
        
        <?php if ($num_vehiculos > 0): ?>
        <div class="row">
        <?php foreach ($response as $car): ?>
        
        <div class="col-lg-3 p-3">


                    <?php
                    $images = glob($car['imagepath'] . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
                    if (!empty($images)) {
                        echo '<img src="' . $images[0] . '" alt="' . $car['marca'] . ' ' . $car['modelo'] . '" width="20%">';
                    } else {
                        echo '<img src="https://via.placeholder.com/200x150?text=No+Image" alt="No Image">';
                    }
                    ?>


                <!-- Car Details (Name, Price, Button) -->
                <div class="car-details">
                    <div>
                        <!-- Year Badge -->
                        <div class="car-year-badge">
                            📅 <?php echo htmlspecialchars($car['anio']); ?>
                        </div>

                        <!-- Car Name -->
                        <h5 class="car-name">
                            <?php echo htmlspecialchars($car['marca'] . ' ' . $car['modelo']); ?>
                        </h5>
                    </div>

                    <!-- Price Container -->
                    <div class="car-price-container">
                        <div class="car-price">
                            $<?php echo number_format($car['preciodiario'], 2); ?>
                            <small>/ day</small>
                        </div>
                    </div>

                    <!-- Rent Button -->
                    <a href="car_details.php?id=<?php echo $car['id_vehiculo']; ?>"
                       class="btn btn-primary btn-rent">
                        View Details
                    </a>
                </div>
            </div>
        </div>
  
    <?php endforeach; ?>
          </div>
    <?php else: ?>
        <div class="empty-state">
            <i>🚗</i>
            <h4>No vehicles available at the moment</h4>
            <p class="text-muted">Please check back later for new additions to our fleet.</p>
        </div>
    <?php endif; ?>
    </div>

</div>

<!-- Footer -->
<footer class="text-center py-4" style="background: #111827; color: #9ca3af;">
    <div class="container">
        <p class="mb-0">&copy; 2024 SECM Rent a Car. All rights reserved.</p>
    </div>
</footer>


</body>
</html>