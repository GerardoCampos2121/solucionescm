<?php

include 'conn.php';

function leerVehiculos(){
    //getConnection instance
    $conn = connectionDB();
    $response = array();
    $response = leerTabla($conn,"vehiculo","id_vehiculo,marca,modelo,anio,preciodiario,imagepath","1");

    return $response;
}

?>