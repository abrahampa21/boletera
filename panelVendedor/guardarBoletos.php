<?php
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['boletos']) && is_array($data['boletos'])) {
    $_SESSION['boletosSeleccionados'] = array_map('intval', $data['boletos']);
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}
?>
