<?php


function connectionDB(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "alquilersecm";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
return $conn;
}

function leerTabla($conn,$tabla,$campos,$condicion){
    $sql = "select ".$campos." from ".$tabla ." where ".$condicion;
    $result = mysqli_query($conn, $sql);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Close connection
    mysqli_close($conn);

return $data;

}

function validarExisteClienteDB($conn,$numeroDocumento){
   $sql = "SELECT 1 FROM cliente WHERE numero_documento = ? LIMIT 1";
   $stmt = mysqli_prepare($conn, $sql);
   mysqli_stmt_bind_param($stmt, "s",$numeroDocumento);
   mysqli_stmt_execute($stmt);
   $result = mysqli_stmt_get_result($stmt);
   $rowCount = mysqli_num_rows($result);
   return $rowCount;
}


function registrarClienteDB($conn, $nombre, $numeroDocumento, $edad, $direccion,$contacto, $correo){
     $sql = "INSERT INTO `cliente`(`nombre`, `numero_documento`, `edad`, `direccion`, `contacto`, `correo`)
     VALUES (?,?,?,?,?,?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssss",$nombre,$numeroDocumento,$edad,$direccion,$contacto, $correo);
    mysqli_stmt_execute($stmt);
    $last_id = mysqli_stmt_insert_id($stmt);
    echo "Inserted ID: " . $last_id;
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

     return $last_id;

}


function recuperarDataVehiculo($conn,$carId){

    // Check if customer exists
    $sql = "SELECT marca, modelo, anio, descripcion,categoria, preciodiario,imagepath
            FROM vehiculo WHERE id_vehiculo = ? LIMIT 1";
    // 2. Prepare statement
    $stmt = $conn->prepare($sql);

    // 3. Bind parameters and execute
    $stmt->bind_param("i", $carId);
    $stmt->execute();

    // 4. Get the result set
    $result = $stmt->get_result();

    // 5. Fetch the single row
    $row = $result->fetch_assoc();

    $conn->close();
    return $row;
}


function registrarReserva($conn, $idCliente,$idVehiculo,$fechaInicio,$fechaFin,$estadoPago,$numeroReserva,
                                $montoTotal,$fechaReserva){
     $sql = "INSERT INTO `cliente`(`id_cliente`, `id_vehiculo`, `fecha_inicio`, `fecha_fin`, `estado_pago`,
                                    `numero_reserva`, `monto_total`,`fecha_reserva`)
     VALUES (?,?,?,?,?,?,?,?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss",$idCliente,$idVehiculo,$fechaInicio,$fechaFin,$estadoPago, $numeroReserva,$montoTotal,$fechaReserva);
    mysqli_stmt_execute($stmt);
    $last_id = mysqli_stmt_insert_id($stmt);
    //echo "Inserted ID: " . $last_id;
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

     return $last_id;

}



?>