<?php
session_start();

$data = json_decode(file_get_contents("php://input"), true);

if (is_array($data)) {
    $_SESSION['cart'] = $data;
}

echo json_encode([
    "status" => "success",
    "items" => count($_SESSION['cart'] ?? [])
]);
