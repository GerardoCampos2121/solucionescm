<?php

include 'conn.php';

function listaVehiculos(){
    //getConnection instance
    $conn = connectionDB();
    $response = array();
    $response = leerTabla($conn,"vehiculo","id_vehiculo,marca,modelo,anio,preciodiario,imagepath","1");

    return $response;
}


function registrarCliente($nombre, $numeroDocumento, $edad, $direccion,$contacto, $correo){

    $conn = connectionDB();
    $edadDB = (int)$edad;
    $response = registrarClienteDB($conn,$nombre, $numeroDocumento, $edad, $direccion,$contacto, $correo);

    return $response;
}

?>