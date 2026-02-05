<?php
require_once '../config/database.php';
include 'auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel | Ashoka EV</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Admin CSS -->
  <link rel="stylesheet" href="assets/css/admin.css">

  <!-- Font Awesome -->
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="admin-dashboard">
  <header class="login-header">
  <!-- MOBILE MENU BUTTON -->
  <button class="menu-toggle" aria-label="Toggle menu">
    <i class="fa fa-bars"></i>
  </button>

  <h2>Welcome, <?= htmlspecialchars($_SESSION['admin_name']) ?></h2>

  <a href="logout.php" class="logout-btn">Logout</a>
</header>

