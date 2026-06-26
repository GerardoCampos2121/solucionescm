<?php
session_start();

include("conf/Service.php");

$carId = 0;
if (!isset($_POST['guardar_frm'])) //si no viene el boton en el post entonces vienen de la pagina anterior
{    
    // Get car ID and dates from URL parameters
    $carId = $_GET['car_id'] ?? null;
    $startDate = $_GET['start_date'] ?? null;
    $endDate = $_GET['end_date'] ?? null;
    $bookingConfirmed = false;

}else{
     // post car ID and dates from URL parameters, si ya existe el boton entonces viene del formulario y se usa post
    $carId = $_POST['car_id'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $bookingConfirmed = false;

}


// If car not found or no dates selected
if (!$carId || !$carId || !$carId) {
    die("Invalid booking information. Please go back and select a car and dates.");
}

$selectedCar = dataVehiculo($carId);
$imageCar = $selectedCar['imagepath']."/1.jpg";

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
        'phone' => $_POST['phone'] ?? '',
        'idCliente' => 0
    ];

    //aqui registrar cliente
    $clientExist = clientExist($customerData['document_id']);
    if($clientExist <= 0 ){
        $response = registrarCliente($customerData['name'],$customerData['document_id'],$customerData['age'],$customerData['address'],
        $customerData['phone'],$customerData['email']);
        echo "idClienteRegistrado: ".$response;
        $customerData['idCliente'] = $response;
    }else{
        $customerData['idCliente'] = $clientExist['id_cliente'];
    }
    
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
                <i class="fas fa-arrow-left me-2"></i>Regresar a fechas de reserva
            </a>
            <h1><i class="fas fa-calendar-check me-2"></i>Completa tu reserva!</h1>
            <p class="mb-0">Completa tu información para confirmar tu reserva!</p>
        </div>
    </div>
            

        <div class="checkout-grid d-flex justify-content-center align-items-center vh-400">
            <div class="form-container">
                <!-- Customer Registration Check Div -->
                <div id="customer-check-section" class="customer-check-card mb-4">
                    <div class="card-header-custom">
                        <h4><i class="fas fa-user-check me-2"></i>Verificación de registro</h4>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted mb-3">Ya eres cliente registrado? Ingresa tu número de documento para verificar y auto completar tu información.</p>
                        <div class="row g-3 align-items-end">
                            <div class="col-md-8">
                                <label for="check_document_id" class="form-label">
                                    <i class="fas fa-id-card me-1"></i>Número de documento
                                </label>
                                <input type="text" class="form-control" id="check_document_id" 
                                       placeholder="Enter your DUI, Passport, or ID number">
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn-check-customer w-100" id="btnCheckCustomer">
                                    <i class="fas fa-search me-1"></i>Consultar
                                </button>
                            </div>
                        </div>
                        <!-- Validation message -->
                        <div id="customer-check-message" class="mt-3" style="display: none;">
                            <div class="alert" role="alert">
                                <i class="fas fa-info-circle me-2"></i>
                                <span id="customer-check-text"></span>
                            </div>
                        </div>
                        <!-- Action buttons for registered customer (dynamically shown/hidden by JS) -->
                        <div id="customer-found-actions" class="mt-3" style="display: none;"></div>
                    </div>
                </div>

                <!-- Booking Form - Three Column Layout -->
                <form method="POST" action="" id="bookingForm">
                    <!-- Hidden fields for car and dates -->
                    <input type="hidden" name="car_id" value="<?php echo $carId; ?>">
                    <input type="hidden" name="start_date" value="<?php echo $startDate; ?>">
                    <input type="hidden" name="end_date" value="<?php echo $endDate; ?>">
                    
                    <div class="row g-2">
                        <!-- Column 1: Primary Details -->
                        <div class="col-lg-4">
                            <div class="booking-card h-100">
                                <div class="card-header-custom">
                                    <h4><i class="fas fa-id-card me-2"></i>Datos personales</h4>
                                </div>
                                <div class="card-body p-4">
                                    <div class="checkout-form">
                                        <label for="name" class="form-label">
                                            <i class="fas fa-user me-1 text-primary"></i>Nombre completo <span class="required">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="name" name="name" required 
                                                placeholder="Your full legal name">                                        
                                        <div class="form-text">Como aparece en tu documento de identificación</div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="document_id" class="form-label">
                                            <i class="fas fa-passport me-1 text-primary"></i>Número de documento <span class="text-danger">*</span>
                                        </label>                                        
                                        <input type="text" class="form-control" id="document_id" name="document_id" required 
                                            placeholder="DUI, Passport, etc.">                                        
                                        <div class="form-text">Documento único legal de identificación</div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="age" class="form-label">
                                            <i class="fas fa-birthday-cake me-1 text-primary"></i>Edad <span class="text-danger">*</span>
                                        </label>                                        
                                        <input type="number" class="form-control" id="age" name="age" required 
                                                min="18" max="100" placeholder="18+">                                        
                                        <div class="form-text">Mínimo 18 años requerido</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Column 2: Contact Information -->
                        <div class="col-lg-4">
                            <div class="booking-card h-100">
                                <div class="card-header-custom">
                                    <h4><i class="fas fa-address-book me-2"></i>Información de contacto</h4>
                                </div>
                                <div class="card-body p-4">
                                    <div class="mb-4">
                                        <label for="address" class="form-label">
                                            <i class="fas fa-home me-1 text-primary"></i>Dirección de entrega vehiculo <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-home"></i>
                                            </span>
                                            <textarea class="form-control" id="address" name="address" rows="3" required 
                                                    placeholder="Dirección donde deseas recibir el vehiculo"></textarea>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="email" class="form-label">
                                            <i class="fas fa-envelope me-1 text-primary"></i>Correo <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-envelope"></i>
                                            </span>
                                            <input type="email" class="form-control" id="email" name="email" required 
                                                placeholder="your@email.com">
                                        </div>
                                        <div class="form-text">Ingresa formato válido</div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="phone" class="form-label">
                                            <i class="fas fa-phone me-1 text-primary"></i>Número de teléfono <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-phone"></i>
                                            </span>
                                            <input type="tel" class="form-control" id="phone" name="phone" required 
                                                placeholder="XXX XXXX-XXXX">
                                        </div>
                                        <div class="form-text">Para actualizaciones de reserva</div>
                                    </div>

                                    <div class="info-box mt-4">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Important:</strong> Toda la información debe ser precisa para fines de verificación.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Column 3: Booking Summary -->
                        <div class="col-lg-4">
                            <div class="booking-card h-100">
                                <div class="card-header-custom">
                                    <h4><i class="fas fa-receipt me-2"></i>Resumen de reserva</h4>
                                </div>
                                <div class="card-body p-4">
                                    <div class="text-center mb-3">
                                        <img src="<?php echo $imageCar; ?>"
                                             alt="<?php echo $selectedCar['marca'] . ' ' . $selectedCar['modelo']; ?>"
                                             style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">
                                    </div>
                                    <h5 class="text-center mb-3"><?php echo $selectedCar['marca'] . ' ' . $selectedCar['modelo']. ' ' . $selectedCar['anio']; ?></h5>
                                    
                                    <div class="info-row">
                                        <span class="info-label">Desde: </span>
                                        <span class="info-value"><?php echo date('M d, Y', strtotime($startDate)); ?></span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Hasta:</span>
                                        <span class="info-value"><?php echo date('M d, Y', strtotime($endDate)); ?></span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Duración</span>
                                        <span class="info-value">
                                            <?php
                                            $days = (strtotime($endDate) - strtotime($startDate)) / 86400;
                                            echo $days . ' días(s)';
                                            ?>
                                        </span>
                                    </div>
                                    
                                    <div class="info-box mt-4">
                                        <i class="fas fa-shield-alt me-2"></i>
                                        <strong>Información de reserva</strong><br>
                                        <small>Tu información será procesada y protegida.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-3 mt-4">
                    <input type="submit" class="btn-confirm" name="guardar_frm" value="Confirmar Reserva" id="guardar_frm">
                
                        <a href="car_details.php?id=<?php echo $carId; ?>" class="btn btn-cancel">
                            <i class="fas fa-times me-2"></i>Cancelar
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