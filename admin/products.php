<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}

$products = mysqli_query($conn, "
  SELECT 
    p.id, 
    p.name, 
    p.price, 
    p.status, 
    b.name AS brand
  FROM products p
  LEFT JOIN brands b ON p.brand_id = b.id
  ORDER BY p.id DESC
");
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<div class="admin-container">
  <h2>Products</h2>

  <a href="product-add.php" class="btn-primary">+ Add Product</a>

  <table class="admin-table mt-3">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Brand</th>
        <th>Price</th>
        <th>Status</th>
        <th style="width:180px;">Action</th>
      </tr>
    </thead>

    <tbody>
      <?php while ($p = mysqli_fetch_assoc($products)): ?>
      <tr>
        <td><?= $p['id'] ?></td>

        <td><?= htmlspecialchars($p['name']) ?></td>

        <td><?= htmlspecialchars($p['brand'] ?? '—') ?></td>

        <td>₹<?= number_format($p['price']) ?></td>

        <td>
          <?= $p['status'] ? '<span class="status-active">Active</span>' 
                           : '<span class="status-inactive">Inactive</span>' ?>
        </td>

        <td class="action-buttons">
          <a href="product-edit.php?id=<?= $p['id'] ?>" class="btn-edit">
            Edit
          </a>

          <a href="product-delete.php?id=<?= $p['id'] ?>"
             class="btn-delete"
             onclick="return confirm('Are you sure you want to delete this product?');">
            Delete
          </a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php include 'includes/footer.php'; ?>
