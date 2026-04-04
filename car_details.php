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

// Get car ID
$id = $_GET['id'] ?? null;

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
        ['from' => '2026-04-04', 'to' => '2026-04-15'],
        ['from' => '2026-04-25', 'to' => '2026-04-30']
    ],
    2 => [
        ['from' => '2026-04-02', 'to' => '2026-04-08']
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
</head>
<body class="bg-light">

<div class="container mt-5">

    <a href="index.php" class="btn btn-secondary mb-4">← Back to Dashboard</a>

    <div class="card shadow">
        <div class="row g-0">

            <!-- Image -->
            <div class="col-md-6">
                <img src="<?php echo $selectedCar['image']; ?>"
                     class="img-fluid rounded-start"
                     style="height:100%; object-fit:cover;">
            </div>

            <!-- Details -->
            <div class="col-md-6">
                <div class="card-body">
                    <h2 class="card-title fw-bold">
                        <?php echo $selectedCar['make'] . ' ' . $selectedCar['model']; ?>
                    </h2>

                    <p>
                        Status:
                        <?php if ($selectedCar['status'] === 'Available'): ?>
                            <span class="badge bg-success">Available</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Rented</span>
                        <?php endif; ?>
                    </p>

                    <hr>

                    <h5>Select Rental Dates</h5>

                    <?php if ($selectedCar['status'] === 'Available'): ?>
                    <form action="booking_form.php" method="get">
                        <div class="mb-3">
                            <label class="form-label">Select Rental Period</label>
                            <input type="text" id="rentalRange" name="rental_range" class="form-control" placeholder="Select date range" required>
                        </div>

                        <!-- Hidden field for car ID -->
                        <input type="hidden" name="car_id" value="<?php echo $selectedCar['id']; ?>">

                        <button type="submit" class="btn btn-success" id="confirmBookingBtn">
                            Confirm Booking
                        </button>
                    </form>
                    <?php else: ?>
                        <div class="alert alert-danger">
                            This vehicle is currently rented.
                        </div>
                    <?php endif; ?>

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
</script>
</body>
</html>