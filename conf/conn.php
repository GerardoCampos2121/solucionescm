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



?>