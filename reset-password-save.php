<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['reset_data'])) {
  header("Location: forgot-password.php");
  exit;
}

$otp      = trim($_POST['otp'] ?? '');
$password = $_POST['password'] ?? '';
$data     = $_SESSION['reset_data'];

if (!$otp || !$password) {
  $_SESSION['fp_error'] = "All fields required";
  header("Location: reset-password.php");
  exit;
}

if (time() > $data['expires']) {
  $_SESSION['fp_error'] = "OTP expired";
  header("Location: reset-password.php");
  exit;
}

if ($otp !== (string)$data['otp']) {
  $_SESSION['fp_error'] = "Invalid OTP";
  header("Location: reset-password.php");
  exit;
}

/* UPDATE PASSWORD */
$hashed = password_hash($password, PASSWORD_BCRYPT);

$stmt = mysqli_prepare(
  $conn,
  "UPDATE users SET password = ? WHERE email = ?"
);
mysqli_stmt_bind_param($stmt, "ss", $hashed, $data['email']);
mysqli_stmt_execute($stmt);

/* CLEANUP */
unset($_SESSION['reset_data']);

header("Location: login.php?reset=1");
exit;
