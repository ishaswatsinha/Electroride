<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}

/* =========================
   COLLECT & VALIDATE INPUT
========================= */

$name        = trim($_POST['name']);
$price       = (int) $_POST['price'];
$category_id = (int) $_POST['category_id'];
$brand_id    = (int) $_POST['brand_id'];
$desc        = trim($_POST['description']);
$status      = 1;

/* BASIC VALIDATION */
if ($name === '' || $price <= 0 || $category_id <= 0 || $brand_id <= 0) {
  die("Invalid input data");
}

/* =========================
   IMAGE UPLOAD
========================= */

$imageName = time() . '_' . basename($_FILES['image']['name']);
$uploadPath = "../uploads/products/" . $imageName;

if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
  die("Image upload failed");
}

$imageDbPath = "uploads/products/" . $imageName;

/* =========================
   INSERT USING PREPARED STATEMENT
========================= */

$stmt = mysqli_prepare($conn, "
  INSERT INTO products
  (category_id, brand_id, name, description, price, image, status)
  VALUES (?, ?, ?, ?, ?, ?, ?)
");

mysqli_stmt_bind_param(
  $stmt,
  "iissisi",
  $category_id,
  $brand_id,
  $name,
  $desc,
  $price,
  $imageDbPath,
  $status
);

if (!mysqli_stmt_execute($stmt)) {
  die("Database error: " . mysqli_error($conn));
}

mysqli_stmt_close($stmt);

/* =========================
   REDIRECT
========================= */

header("Location: products.php?success=1");
exit;
