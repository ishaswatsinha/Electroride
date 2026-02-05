<?php
session_start();
require_once '../config/database.php';

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$password = $_POST['password'];

if (!$name || !$email || !$password) {
  $_SESSION['error'] = "All required fields missing";
  header("Location: ../signup.php");
  exit;
}

/* Check email exists */
$check = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
mysqli_stmt_bind_param($check, "s", $email);
mysqli_stmt_execute($check);
$res = mysqli_stmt_get_result($check);

if (mysqli_num_rows($res) > 0) {
  $_SESSION['error'] = "Email already registered";
  header("Location: ../signup.php");
  exit;
}

/* Generate OTP */
$otp = rand(100000, 999999);
$expires = date("Y-m-d H:i:s", strtotime("+10 minutes"));
$hashed = password_hash($password, PASSWORD_DEFAULT);

/* Insert user */
$stmt = mysqli_prepare(
  $conn,
  "INSERT INTO users (name, email, phone, password, otp_code, otp_expires)
   VALUES (?, ?, ?, ?, ?, ?)"
);
mysqli_stmt_bind_param($stmt, "ssssss",
  $name, $email, $phone, $hashed, $otp, $expires
);
mysqli_stmt_execute($stmt);

/* Send OTP */
$subject = "Verify your email - Ashoka EV";
$message = "Your OTP is: $otp\nValid for 10 minutes.";
$headers = "From: no-reply@ashokaev.com";

mail($email, $subject, $message, $headers);

/* Redirect to OTP page */
$_SESSION['otp_email'] = $email;
header("Location: ../verify-otp.php");
exit;
