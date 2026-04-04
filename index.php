<?php
$cars = [
    [
        'id' => 1,
        'make' => 'Toyota',
        'model' => 'Corolla',
        'status' => 'Available',
        'image' => 'images/corolla.png'
    ],
    [
        'id' => 2,
        'make' => 'Honda',
        'model' => 'Civic',
        'status' => 'Rented',
        'image' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=200&q=60'
    ],
    [
        'id' => 3,
        'make' => 'Kia',
        'model' => 'Forte',
        'status' => 'Available',
        'image' => 'images/forte2014.jpeg'
    ],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Car Rental Dashboard</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

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
                    <a class="nav-link active" href="index.php">Dashboard</a>
                </li>
                <!--<li class="nav-item">
                    <a class="nav-link" href="cars.php">Manage Cars</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Bookings</a>
                </li>-->
            </ul>
        </div>
    </div>
</nav>

<!-- 🔷 MAIN CONTENT -->
<div class="container mt-5">

    <!-- slide -->
    <!-- 🚗 Car Preview Slider -->
    <div id="carCarousel" class="carousel slide mb-4 shadow" data-bs-ride="carousel">

        <div class="carousel-indicators">
            <?php foreach ($cars as $index => $car): ?>
                <button type="button"
                        data-bs-target="#carCarousel"
                        data-bs-slide-to="<?php echo $index; ?>"
                        class="<?php echo $index === 0 ? 'active' : ''; ?>">
                </button>
            <?php endforeach; ?>
        </div>

        <div class="carousel-inner rounded">

            <?php foreach ($cars as $index => $car): ?>
            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">

                <img src="<?php echo $car['image']; ?>"
                     class="d-block w-100"
                     style="height: 400px; object-fit: cover;"
                     alt="Car Image">

                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
                    <h4 class="fw-bold">
                        <?php echo $car['make'] . ' ' . $car['model']; ?>
                    </h4>
                    <span class="badge <?php echo $car['status'] === 'Available' ? 'bg-success' : 'bg-danger'; ?>">
                        <?php echo $car['status']; ?>
                    </span>
                </div>

            </div>
            <?php endforeach; ?>

        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#carCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#carCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>

    </div>


    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Disponibles</h5>
                    <p class="card-text fs-4">
                        <?php echo count(array_filter($cars, fn($c) => $c['status'] === 'Available')); ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-danger shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Ocupados</h5>
                    <p class="card-text fs-4">
                        <?php echo count(array_filter($cars, fn($c) => $c['status'] === 'Rented')); ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-dark shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Cars</h5>
                    <p class="card-text fs-4">
                        <?php echo count($cars); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Cars Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Car List</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Vehicle</th>
                            <th>Status</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cars as $car): ?>
                        <tr>
                            <td><?php echo $car['id']; ?></td>

                            <!-- Vehicle Column with Image -->
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo $car['image']; ?>"
                                         alt="Car Image"
                                         class="rounded me-3 shadow-sm"
                                         width="80" height="60"
                                         style="object-fit: cover;">

                                    <div>
                                        <strong><?php echo $car['make']; ?></strong><br>
                                        <small class="text-muted"><?php echo $car['model']; ?></small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <?php if ($car['status'] === 'Available'): ?>
                                    <span class="badge bg-success">Available</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Rented</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($car['status'] === 'Available'): ?>
                                <a href="car_details.php?id=<?php echo $car['id']; ?>"
                                   class="btn btn-sm btn-dark">
                                    Ver
                                </a>
                             <?php else: ?>
                             <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>