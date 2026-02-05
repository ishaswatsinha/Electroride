<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: signup.php");
    exit;
}

if (!isset($_SESSION['signup_data'])) {
    $_SESSION['otp_error'] = "Session expired. Please sign up again.";
    header("Location: signup.php");
    exit;
}

$data = $_SESSION['signup_data'];
$userOtp = trim($_POST['otp'] ?? '');

if ($userOtp === '') {
    $_SESSION['otp_error'] = "Please enter OTP.";
    header("Location: verify-otp.php");
    exit;
}

if (time() > $data['expires']) {
    $_SESSION['otp_error'] = "OTP expired. Please resend.";
    header("Location: verify-otp.php");
    exit;
}

if ($userOtp !== (string)$data['otp']) {
    $_SESSION['otp_error'] = "Invalid OTP.";
    header("Location: verify-otp.php");
    exit;
}

/* INSERT USER */
$stmt = mysqli_prepare($conn, "
  INSERT INTO users (name, email, mobile, password)
  VALUES (?, ?, ?, ?)
");

mysqli_stmt_bind_param(
  $stmt,
  "ssss",
  $data['name'],
  $data['email'],
  $data['mobile'],
  $data['password']
);

mysqli_stmt_execute($stmt);

/* CLEAN TEMP DATA */
unset($_SESSION['signup_data']);

/* SUCCESS FLAG */
$_SESSION['otp_success'] = true;

header("Location: verify-otp.php");
exit;
