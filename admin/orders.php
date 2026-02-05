<?php
include 'includes/header.php';
include 'includes/sidebar.php';

$q = mysqli_query($conn, "
  SELECT id, customer_name, phone, total_amount, status, created_at
  FROM orders
  ORDER BY id DESC
");
?>

<div class="admin-content">
  <h1>Orders</h1>

  <table class="admin-table">
    <thead>
      <tr>
        <th>#</th>
        <th>Customer</th>
        <th>Phone</th>
        <th>Total</th>
        <th>Status</th>
        <th>Date</th>
        <th>Action</th>
      </tr>
    </thead>

    <tbody>
      <?php while ($row = mysqli_fetch_assoc($q)): ?>
      <tr>
        <td>#<?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['customer_name']) ?></td>
        <td><?= htmlspecialchars($row['phone']) ?></td>
        <td>₹<?= number_format($row['total_amount']) ?></td>
        <td>
          <span class="badge <?= strtolower($row['status']) ?>">
            <?= $row['status'] ?>
          </span>
        </td>
        <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
        <td>
          <a href="order-view.php?id=<?= $row['id'] ?>" class="btn-view">
            View
          </a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php include 'includes/footer.php'; ?>
