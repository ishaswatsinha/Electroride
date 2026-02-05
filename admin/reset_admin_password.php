<?php
require_once '../config/database.php';

$newPassword = 'admin123'; // 👈 choose your password
$hash = password_hash($newPassword, PASSWORD_DEFAULT);

$sql = "UPDATE admins SET password = ? WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);
$email = 'admin@ashokaev.com';

mysqli_stmt_bind_param($stmt, "ss", $hash, $email);
mysqli_stmt_execute($stmt);

echo "Password reset successful.<br>";
echo "Email: admin@ashokaev.com<br>";
echo "Password: admin123";
