<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../libs/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../libs/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer/src/SMTP.php';

require_once __DIR__ . '/../config/mail.php';

function sendOtpMail($toEmail, $otp)
{
    $mail = new PHPMailer(true);

    try {
        // SMTP CONFIG
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = MAIL_PORT;

        // SENDER
        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress($toEmail);

        // CONTENT
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Verification Code';

        $mail->Body = "
            <h2>Ashoka EV - Email Verification</h2>
            <p>Your OTP is:</p>
            <h1 style='letter-spacing:3px;'>$otp</h1>
            <p>This OTP is valid for <strong>10 minutes</strong>.</p>
            <br>
            <p>Do not share this OTP with anyone.</p>
        ";

        $mail->send();
        return true;

    } catch (Exception $e) {
        return false;
    }
}
