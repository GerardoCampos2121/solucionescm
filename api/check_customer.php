<?php
session_start();
header('Content-Type: application/json');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type');
    exit(0);
}

include("../conf/conn.php");

$response = [
    'success' => false,
    'exists' => false,
    'message' => '',
    'customer' => null
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documentId = isset($_POST['document_id']) ? trim($_POST['document_id']) : '';
    
    if (empty($documentId)) {
        $response['message'] = 'Document ID is required';
        echo json_encode($response);
        exit;
    }
    
    $conn = connectionDB();
    
    // Check if customer exists
    $sql = "SELECT id_cliente, nombre, numero_documento, edad, direccion, contacto, correo 
            FROM cliente WHERE numero_documento = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $documentId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $response['success'] = true;
        $response['exists'] = true;
        $response['message'] = 'Customer found';
        $response['customer'] = $row;
    } else {
        $response['success'] = true;
        $response['exists'] = false;
        $response['message'] = 'Customer not found. Please complete the registration form.';
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

echo json_encode($response);
?>