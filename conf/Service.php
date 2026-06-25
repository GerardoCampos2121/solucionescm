<?php

include 'conn.php';

function listaVehiculos(){
    //getConnection instance
    $conn = connectionDB();
    $response = array();
    $response = leerTabla($conn,"vehiculo","id_vehiculo,marca,modelo,anio,preciodiario,imagepath","1");

    return $response;
}

function dataVehiculo($carId){

    //getConnection instance
    $conn = connectionDB();
    $response = array();
    $response = recuperarDataVehiculo($conn,$carId);
    return $response;
}

function clientExist($numeroDocumento){
    $conn = connectionDB();
    $response = validarExisteClienteDB($conn,$numeroDocumento);
    return $response;
}


function registrarCliente($nombre, $numeroDocumento, $edad, $direccion,$contacto, $correo){

    $conn = connectionDB();
    $edadDB = (int)$edad;
    $response = registrarClienteDB($conn,$nombre, $numeroDocumento, $edad, $direccion,$contacto, $correo);

    return $response;
}

function reservarVehiculo($idCliente,$carId,$fechaInicio,$fechaFin,
                            $estadoPago,$numeroReserva,$montoTotal,$fechaReserva){

         $conn = connectionDB();
         $idVehiculo = (int)$carId;
         $response = registrarReserva($conn,$idCliente,$idVehiculo,$fechaInicio,$fechaFin,
                                      $estadoPago,$numeroReserva,$montoTotal,$fechaReserva);

         return $response;
}

?>