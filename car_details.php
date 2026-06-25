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
$carBookedRanges = $bookedDates[$id] ?? [];

 // 1. Define the directory path containing your images
 $dir = $vehiculoSeleccionado['imagepath'];

 // 2. Fetch all image files matching common extensions
$images = glob($dir . "*.{jpg,jpeg,png,gif,webp}", GLOB_BRACE);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Car Details</title>
    <!-- Bootstrap 5 CDN -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
     <!-- Bootstrap JS -->
    <script src="js/bootstrap.bundle.min.js"></script>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>

            

            .btn-propio {
                    display: inline-block;
                    padding: 0.375rem 0.75rem;
                    font-size: 1rem;
                    font-weight: 400;
                    line-height: 1.5;
                    text-align: center;
                    text-decoration: none;
                    color: #fff;
                    background-color: #0d6efd;
                    border: 1px solid #0d6efd;
                    border-radius: 0.375rem;
                    cursor: pointer;
                    transition: all 0.15s ease-in-out;
            }
        </style>
  <?php include("pages/header.php");?>
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
                    <a class="nav-link active" href="index.php">Inicio</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">

    <a href="index.php" class="btn btn-secondary mb-4">← Regresar a Inicio</a>
    
        <div class="card shadow">
        <div class="row ">

            <!-- Image -->
            <div class="col-xl-6 col-md-2">
            
            <div id="carouselExampleSlidesOnly1" class="carousel slide" data-bs-ride="carousel"  data-bs-interval="2000">
                    <div class="carousel-inner">
                        <!--div class="carousel-item active">
                        <img src="images/kiaforte2018/corolla1.jpg" class="d-block w-100" alt="First slide">
                        </div-->
                        <?php
                        $i=0;
                        $active="";
                        if (!empty($images)) {
                                        foreach ($images as $image) {
                                            if ($i==0)
                                                $active="active";
                                            echo '<div class="carousel-item '.$active.'"><img src="' . htmlspecialchars($image) . '" class="d-block w-100" alt="Slider Image"></div>';
                                            $i++;
                                            $active="";
                                        }
                                    }
                        ?>
                    </div>
            </div>
                    

            </div>    

            <!-- Details -->
            <div class="col-xl-6 col-md-3">
                <div class="card-body">
                    <h2 class="card-title fw-bold">
                        <?php echo $vehiculoSeleccionado['marca'] . ' ' . $vehiculoSeleccionado['modelo']
                        . ' ' .$vehiculoSeleccionado['anio']; ?>
                    </h2>

                    <p>
                        Status:
                            <span class="badge bg-success">Available</span>
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
                        <input type="hidden" name="car_id" value="<?php echo $id; ?>">
                        <div>
                            <button type="submit" class="btn btn-primary" id="confirmBookingBtn">
                                Continuar Reserva
                        </button>
                             
                        </div>
                       
                        
                      
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

    /*

    let currentIndex = 0;
    const slidesContainer = document.querySelector('.slides');
    const totalSlides = document.querySelectorAll('.slide').length;

    /*function updateSlider() {
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
    }, 3000);*/
</script>

</body>
</html>