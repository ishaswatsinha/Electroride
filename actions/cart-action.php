<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_POST['action'], $_POST['id'])) {
    header("Location: ../cart.php");
    exit;
}

$action = $_POST['action'];
$id     = (int) $_POST['id'];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* =========================
   ADD TO CART
========================= */
if ($action === 'add') {

    $stmt = mysqli_prepare(
        $conn,
        "SELECT id, name, price, image 
         FROM products 
         WHERE id = ? AND status = 1"
    );
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($res);

    if (!$product) {
        header("Location: ../cart.php");
        exit;
    }

    foreach ($_SESSION['cart'] as &$item) {
        if ((int)$item['id'] === $id) {
            $item['qty']++;
            header("Location: " . ($_POST['redirect'] ?? '../cart.php'));
            exit;
        }
    }

    $_SESSION['cart'][] = [
        'id'    => $product['id'],
        'name'  => $product['name'],
        'price' => (int)$product['price'],
        'image' => $product['image'],
        'qty'   => 1
    ];

    header("Location: " . ($_POST['redirect'] ?? '../cart.php'));
    exit;
}

header("Location: ../cart.php");
exit;
