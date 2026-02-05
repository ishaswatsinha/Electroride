<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    $_SESSION['login_error'] = "All fields are required.";
    header("Location: login.php");
    exit;
}

/* FETCH USER */
$stmt = mysqli_prepare(
    $conn,
    "SELECT id, name, email, password FROM users WHERE email = ?"
);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

/* CHECK EMAIL */
if (!$user) {
    $_SESSION['login_error'] = "Invalid email or password.";
    header("Location: login.php");
    exit;
}

/* VERIFY PASSWORD */
if (!password_verify($password, $user['password'])) {
    $_SESSION['login_error'] = "Invalid email or password.";
    header("Location: login.php");
    exit;
}

/* LOGIN SUCCESS */
$_SESSION['user'] = [
    'id'    => $user['id'],
    'name'  => $user['name'],
    'email' => $user['email']
];

header("Location: index.php");
exit;
