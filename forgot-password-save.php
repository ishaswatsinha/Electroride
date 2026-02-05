<?php
session_start();
require_once 'config/database.php';
require_once 'includes/send-otp-mail.php';

$email = trim($_POST['email'] ?? '');

if (!$email) {
  $_SESSION['fp_error'] = "Email is required";
  header("Location: forgot-password.php");
  exit;
}

/* CHECK USER */
$stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (!mysqli_fetch_assoc($res)) {
  $_SESSION['fp_error'] = "Email not registered";
  header("Location: forgot-password.php");
  exit;
}

/* GENERATE OTP */
$otp = rand(100000, 999999);

$_SESSION['reset_data'] = [
  'email'   => $email,
  'otp'     => $otp,
  'expires' => time() + 600
];

sendOtpMail($email, $otp);

header("Location: reset-password.php");
exit;
