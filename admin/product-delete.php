<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header("Location: product.php");
  exit;
}

$product_id = (int)$_GET['id'];

/* Get image path */
$res = mysqli_query(
  $conn,
  "SELECT image FROM products WHERE id = $product_id LIMIT 1"
);

$product = mysqli_fetch_assoc($res);

if (!$product) {
  header("Location: product.php");
  exit;
}

/* Delete image file */
if (!empty($product['image'])) {
  $path = "../" . $product['image'];
  if (file_exists($path)) {
    unlink($path);
  }
}

/* Delete product */
mysqli_query(
  $conn,
  "DELETE FROM products WHERE id = $product_id"
);

header("Location: products.php");
exit;
