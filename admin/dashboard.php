<?php
include 'includes/header.php';
include 'includes/sidebar.php';

/* ===========================
   DASHBOARD DATA
=========================== */

// Total Orders
$qOrders = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders");
$totalOrders = mysqli_fetch_assoc($qOrders)['total'] ?? 0;

// Total Revenue
$qRevenue = mysqli_query($conn, "SELECT SUM(total_amount) AS total FROM orders");
$totalRevenue = mysqli_fetch_assoc($qRevenue)['total'] ?? 0;

// Total Products
$qProducts = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
$totalProducts = mysqli_fetch_assoc($qProducts)['total'] ?? 0;

// Pending Orders
$qPending = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE status='Pending'");
$pendingOrders = mysqli_fetch_assoc($qPending)['total'] ?? 0;
?>

<div class="admin-content">

  <h1 class="dashboard-title">Dashboard</h1>

  <div class="dashboard-cards">

    <div class="dash-card card-orders">
      <h4>Total Orders</h4>
      <p><?= $totalOrders ?></p>
    </div>

    <div class="dash-card card-revenue">
      <h4>Total Revenue</h4>
      <p>₹<?= number_format($totalRevenue) ?></p>
    </div>

    <div class="dash-card card-products">
      <h4>Total Products</h4>
      <p><?= $totalProducts ?></p>
    </div>

    <div class="dash-card card-pending">
      <h4>Pending Orders</h4>
      <p><?= $pendingOrders ?></p>
    </div>

  </div>

</div>

<?php include 'includes/footer.php'; ?>
