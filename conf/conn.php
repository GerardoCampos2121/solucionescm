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


function registrarClienteDB($conn, $nombre, $numeroDocumento, $edad, $direccion,$contacto, $correo){
     $sql = "INSERT INTO `cliente`(`nombre`, `numero_documento`, `edad`, `direccion`, `contacto`, `correo`)
     VALUES (?,?,?,?,?,?)";

    echo "Query is ".$sql;

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssss",$nombre,$numeroDocumento,$edad,$direccion,$contacto, $correo);
    mysqli_stmt_execute($stmt);
    $last_id = mysqli_stmt_insert_id($stmt);
    echo "Inserted ID: " . $last_id;
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

     return $last_id;

}



?>