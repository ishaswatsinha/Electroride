<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}

/* FETCH CATEGORIES */
$categories = mysqli_query(
  $conn,
  "SELECT id, name FROM categories WHERE status = 1 ORDER BY name"
);

/* FETCH BRANDS */
$brands = mysqli_query(
  $conn,
  "SELECT id, name FROM brands WHERE status = 1 ORDER BY name"
);
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<div class="admin-container">

  <h2>Add Product</h2>

  <form method="post"
        action="product-save.php"
        enctype="multipart/form-data"
        class="admin-form">

    <!-- PRODUCT NAME -->
    <input type="text"
           name="name"
           placeholder="Product Name"
           required>

    <!-- PRICE -->
    <input type="number"
           name="price"
           placeholder="Price"
           min="1"
           required>

    <!-- CATEGORY -->
    <select name="category_id" required>
      <option value="">Select Category</option>
      <?php while ($c = mysqli_fetch_assoc($categories)): ?>
        <option value="<?= $c['id'] ?>">
          <?= htmlspecialchars($c['name']) ?>
        </option>
      <?php endwhile; ?>
    </select>

    <!-- BRAND -->
    <select name="brand_id" required>
      <option value="">Select Brand</option>
      <?php while ($b = mysqli_fetch_assoc($brands)): ?>
        <option value="<?= $b['id'] ?>">
          <?= htmlspecialchars($b['name']) ?>
        </option>
      <?php endwhile; ?>
    </select>

    <!-- DESCRIPTION -->
    <textarea name="description"
              placeholder="Product Description"
              rows="4"></textarea>

    <!-- IMAGE -->
    <input type="file"
           name="image"
           accept="image/*"
           required>

    <!-- SUBMIT -->
    <button type="submit" class="btn-primary">
      Save Product
    </button>

  </form>

</div>

<?php include 'includes/footer.php'; ?>