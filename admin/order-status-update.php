<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}

$order_id = (int)$_POST['order_id'];
$status   = trim($_POST['status']);

$allowed = ['Pending','Processing','Completed','Cancelled'];
if (!in_array($status, $allowed)) {
  header("Location: orders.php");
  exit;
}

mysqli_query($conn, "
  UPDATE orders 
  SET status = '$status'
  WHERE id = $order_id
");

header("Location: order-view.php?id=$order_id");
exit;
