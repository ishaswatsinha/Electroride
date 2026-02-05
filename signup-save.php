<?php
session_start();
require_once 'config/database.php';
require_once 'includes/send-otp-mail.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: signup.php");
    exit;
}

$name     = trim($_POST['name']);
$email    = trim($_POST['email']);
$mobile   = trim($_POST['mobile']);
$password = $_POST['password'];

if (!$name || !$email || !$mobile || !$password) {
    $_SESSION['error'] = "All fields are required";
    header("Location: signup.php");
    exit;
}

/* CHECK EMAIL EXISTS */
$check = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
mysqli_stmt_bind_param($check, "s", $email);
mysqli_stmt_execute($check);
mysqli_stmt_store_result($check);

if (mysqli_stmt_num_rows($check) > 0) {
    $_SESSION['error'] = "Email already registered";
    header("Location: signup.php");
    exit;
}

/* GENERATE OTP */
$otp = rand(100000, 999999);

/* STORE TEMP USER */
$_SESSION['signup_data'] = [
    'name'     => $name,
    'email'    => $email,
    'mobile'   => $mobile,
    'password' => password_hash($password, PASSWORD_BCRYPT),
    'otp'      => $otp,
    'expires'  => time() + 600 // ✅ SINGLE SOURCE
];

/* SEND MAIL */
sendOtpMail($email, $otp);

/* REDIRECT */
header("Location: verify-otp.php");
exit;
