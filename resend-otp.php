<?php
session_start();

if (!isset($_SESSION['signup_data'])) {
    header("Location: signup.php");
    exit;
}

$otp = rand(100000, 999999);

$_SESSION['signup_data']['otp'] = $otp;
$_SESSION['signup_data']['expires'] = time() + 600;

require_once 'includes/send-otp-mail.php';
sendOtpMail($_SESSION['signup_data']['email'], $otp);

/* OPTIONAL INFO MESSAGE */
$_SESSION['otp_info'] = "A new OTP has been sent.";

header("Location: verify-otp.php");
exit;
