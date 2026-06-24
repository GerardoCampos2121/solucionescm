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
<div class="container mt-5">
    
    <!-- Welcome Section -->
    <div class="row">
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold" style="color: #111827;">Welcome to SECM Rent a Car</h1>
            <p class="text-muted lead">Choose from our selection of quality vehicles</p>
        </div>
    </div>
    <div class="row">
    <?php
    $i=0;
    for($i=0;$i<$num_vehiculos;$i++){
        $c=1;
    ?>   
        <div class="col-md-4">
            <div class="card" style="width: 18rem;">

                <?php
                                    $images = glob($response[$i]['imagepath'] . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
                                    if (!empty($images)) {
                                        echo '<img src="' . $images[0] . '" alt="' . $response[$i]['marca'] . ' ' . $response[$i]['modelo'] . '" width="100%">';
                                    } else {
                                        echo '<img src="https://via.placeholder.com/200x150?text=No+Image" alt="No Image">';
                                    }
                                    ?>

                <div class="card-body">
                    <h5 class="card-title"><center><?= $response[$i]["marca"]." ".$response[$i]["modelo"] ?></center></h5>
                    <p class="card-text">
                                       <center>$ <?= number_format($response[$i]['preciodiario'], 2); ?> / Día</center></p>
                                       <!-- aqui en el href poner el link a donde va a reserevar y ponerle el id -->
                                       <center><a href="car_details.php?id=<?= $response[$i]["id_vehiculo"]?>" class="btn btn-primary">Reservar</a><center>
                 </div>
            </div>
            <center><br>
                    
            </center>
        </div>
    <?php
    $c++;
     } 
    ?>
    </div>
  
</div><!-- fin del container -->

<!-- Footer -->
<footer class="text-center py-4" style="background: #111827; color: #9ca3af;">
    <div class="container">
        <p class="mb-0">&copy; 2024 SECM Rent a Car. All rights reserved.</p>
    </div>
</footer>
</body>