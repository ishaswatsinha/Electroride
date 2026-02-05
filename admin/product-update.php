<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}

/* VALIDATE */
$id          = (int)$_POST['id'];
$name        = mysqli_real_escape_string($conn, $_POST['name']);
$price       = (int)$_POST['price'];
$category_id = (int)$_POST['category_id'];
$brand_id    = (int)$_POST['brand_id'];
$desc        = mysqli_real_escape_string($conn, $_POST['description']);

/* FETCH CURRENT IMAGE */
$current = mysqli_fetch_assoc(
  mysqli_query($conn, "SELECT image FROM products WHERE id = $id")
);

$imagePath = $current['image'];

/* IMAGE UPDATE (OPTIONAL) */
if (!empty($_FILES['image']['name'])) {

  $imageName = time() . '_' . $_FILES['image']['name'];
  $target = "../uploads/products/$imageName";

  if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
    $imagePath = "uploads/products/$imageName";
  }
}

/* UPDATE QUERY */
mysqli_query($conn, "
  UPDATE products SET
    name = '$name',
    price = $price,
    category_id = $category_id,
    brand_id = $brand_id,
    description = '$desc',
    image = '$imagePath'
  WHERE id = $id
");

header("Location: products.php");
exit;
