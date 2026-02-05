<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}

/* VALIDATE PRODUCT ID */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header("Location: products.php");
  exit;
}

$product_id = (int)$_GET['id'];

/* FETCH PRODUCT */
$product_q = mysqli_query(
  $conn,
  "SELECT * FROM products WHERE id = $product_id LIMIT 1"
);

$product = mysqli_fetch_assoc($product_q);

if (!$product) {
  header("Location: products.php");
  exit;
}

/* FETCH CATEGORIES & BRANDS */
$categories = mysqli_query(
  $conn,
  "SELECT id, name FROM categories WHERE status = 1 ORDER BY name"
);

$brands = mysqli_query(
  $conn,
  "SELECT id, name FROM brands WHERE status = 1 ORDER BY name"
);
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<div class="admin-container">

  <h2>Edit Product</h2>

  <form method="post"
        action="product-update.php"
        enctype="multipart/form-data"
        class="admin-form">

    <input type="hidden" name="id" value="<?= $product['id'] ?>">

    <!-- PRODUCT NAME -->
    <input type="text"
           name="name"
           value="<?= htmlspecialchars($product['name']) ?>"
           required>

    <!-- PRICE -->
    <input type="number"
           name="price"
           value="<?= $product['price'] ?>"
           required>

    <!-- CATEGORY -->
    <select name="category_id" required>
      <option value="">Select Category</option>
      <?php while ($c = mysqli_fetch_assoc($categories)): ?>
        <option value="<?= $c['id'] ?>"
          <?= $c['id'] == $product['category_id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($c['name']) ?>
        </option>
      <?php endwhile; ?>
    </select>

    <!-- BRAND -->
    <select name="brand_id" required>
      <option value="">Select Brand</option>
      <?php while ($b = mysqli_fetch_assoc($brands)): ?>
        <option value="<?= $b['id'] ?>"
          <?= $b['id'] == $product['brand_id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($b['name']) ?>
        </option>
      <?php endwhile; ?>
    </select>

    <!-- DESCRIPTION -->
    <textarea name="description"
              rows="4"><?= htmlspecialchars($product['description']) ?></textarea>

    <!-- CURRENT IMAGE -->
    <p>Current Image:</p>
    <img src="../<?= $product['image'] ?>"
         style="max-width:120px; margin-bottom:10px;">

    <!-- NEW IMAGE -->
    <input type="file" name="image" accept="image/*">

    <button type="submit" class="btn-primary">
      Update Product
    </button>

  </form>

</div>
<?php include 'includes/footer.php'; ?>

