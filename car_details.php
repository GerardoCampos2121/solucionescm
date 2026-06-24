<?php

// Same car data (later this will come from database)
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

include("conf/Service.php");


// Get car ID
$id = $_GET['id'] ?? null;

$vehiculoSeleccionado = dataVehiculo($id);
$caracteristicas = explode(";",$vehiculoSeleccionado['descripcion']);
$c = count($caracteristicas);

// Find selected car
$selectedCar = null;
foreach ($cars as $car) {
    if ($car['id'] == $id) {
        $selectedCar = $car;
        break;
    }
}

// If car not found
if (!$selectedCar) {
    die("Car not found.");
}


// Example occupied ranges per car
$bookedDates = [
    1 => [
        ['from' => '2026-06-04', 'to' => '2026-06-05'],
        ['from' => '2026-06-06', 'to' => '2026-06-07']
    ],
    2 => [
        ['from' => '2026-05-02', 'to' => '2026-05-08']
    ],
    3 => []
];

// Get this car's booked dates
$carBookedRanges = $bookedDates[$selectedCar['id']] ?? [];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Car Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>

            /* Slider Container */
            .slider-container { position: relative; width: 600px; height: 400px; overflow: hidden; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); }
            .slides { display: flex; width: 100%; height: 100%; transition: transform 0.5s ease-in-out; }
            .slide { min-width: 100%; height: 100%; }
            .slide img { width: 100%; height: 100%; object-fit: cover; }

            /* Navigation Buttons */
            .btn { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.5); color: white; border: none; padding: 15px; cursor: pointer; font-size: 18px; border-radius: 50%; user-select: none; }
            .btn:hover { background: rgba(0,0,0,0.8); }
            .prev { left: 10px; }
            .next { right: 10px; }
        </style>
    <?php include("pages/header.php");?>
</head>
<body class="bg-light">

<div class="container mt-5">

    <a href="index.php" class="btn btn-secondary mb-4">← Regresar a Inicio</a>

    <div class="card shadow">
        <div class="row g-0">

            <!-- Image -->
            <div class="col-md-6">
                <div class="slider-container">
                    <div class="slides">

                        <?php
                        // 1. Define the directory path containing your images
                        $dir = $vehiculoSeleccionado['imagepath'];

                        // 2. Fetch all image files matching common extensions
                        $images = glob($dir . "*.{jpg,jpeg,png,gif,webp}", GLOB_BRACE);

                        // 3. Loop through the array and render each image inside a slide div
                        if (!empty($images)) {
                            foreach ($images as $image) {
                                echo '<div class="slide"><img src="' . htmlspecialchars($image) . '" alt="Slider Image"></div>';
                            }
                        } else {
                            echo '<div class="slide" style="display:flex; justify-content:center; align-items:center; background:#ccc;">No images found.</div>';
                        }
                        ?>

                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="col-md-6">
                <div class="card-body">
                    <h2 class="card-title fw-bold">
                        <?php echo $vehiculoSeleccionado['marca'] . ' ' . $vehiculoSeleccionado['modelo']
                        . ' ' .$vehiculoSeleccionado['anio']; ?>
                    </h2>

                    <p>
                        Status:
                        <?php if ($selectedCar['status'] === 'Available'): ?>
                            <span class="badge bg-success">Available</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Rented</span>
                        <?php endif; ?>
                    </p>

                    <p>Descripción:</p>
                    <ul>
                        <?php for($i=0;$i<$c;$i++){
                            echo "<li>$caracteristicas[$i]</li>";
                        } ?>
                    </ul>

                    <hr>

                    <h5>Seleccione el periodo de fechas</h5>


                    <form action="booking_form.php" method="GET">
                        <div class="mb-3">
                            <label class="form-label">Periodo de alquiler</label>
                            <input type="text" id="rentalRange" name="rental_range" class="form-control" placeholder="Select date range" required>
                        </div>

                        <!-- Hidden field for car ID -->
                        <input type="hidden" name="car_id" value="<?php echo $selectedCar['id']; ?>">
</br></br></br>
                        <button type="submit" class="btn btn-success" id="confirmBookingBtn">
                            Continuar Reserva
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    const bookedRanges = <?php echo json_encode($carBookedRanges); ?>;

    flatpickr("#rentalRange", {
        mode: "range",
        minDate: "today",
        dateFormat: "Y-m-d",

        disable: bookedRanges.map(range => {
            return {
                from: range.from,
                to: range.to
            }
        })
    });

    // Handle form submission to pass dates as separate parameters
    document.querySelector('form').addEventListener('submit', function(e) {
        var rentalRange = document.getElementById('rentalRange').value;
        
        // Parse the date range (format: "YYYY-MM-DD to YYYY-MM-DD")
        var dates = rentalRange.split(' to ');
        
        if (dates.length !== 2) {
            e.preventDefault();
            alert('Please select a valid date range.');
            return false;
        }
        
        // Create hidden inputs for start_date and end_date
        var startDateInput = document.createElement('input');
        startDateInput.type = 'hidden';
        startDateInput.name = 'start_date';
        startDateInput.value = dates[0];
        this.appendChild(startDateInput);
        
        var endDateInput = document.createElement('input');
        endDateInput.type = 'hidden';
        endDateInput.name = 'end_date';
        endDateInput.value = dates[1];
        this.appendChild(endDateInput);
    });

    let currentIndex = 0;
    const slidesContainer = document.querySelector('.slides');
    const totalSlides = document.querySelectorAll('.slide').length;

    function updateSlider() {
        // Shift the slide viewport container left/right based on current index
        slidesContainer.style.transform = `translateX(-${currentIndex * 100}%)`;
    }

    function moveSlide(direction) {
        currentIndex += direction;
        // Loop back to first or last slide if bounds are exceeded
        if (currentIndex >= totalSlides) currentIndex = 0;
        if (currentIndex < 0) currentIndex = totalSlides - 1;
        updateSlider();
    }

    // Optional: Auto-play the slider every 4 seconds
    setInterval(() => {
        moveSlide(1);
    }, 3000);
</script>

</body>
</html>