<?php
session_start();
require_once __DIR__ . '/config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) die("Invalid product");

$stmt = mysqli_prepare(
    $conn,
    "SELECT 
        p.id, p.name, p.price, p.image, p.description,
        b.name AS brand, c.name AS category
     FROM products p
     JOIN brands b ON p.brand_id = b.id
     JOIN categories c ON p.category_id = c.id
     WHERE p.id = ? AND p.status = 1"
);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($res);

if (!$product) die("Product not found");

include 'includes/header.php';
?>

<div class="product-page">
  <div class="product-container">

    <!-- IMAGE CARD -->
    <div class="product-image-card">
      <img
        src="/ashoka-electroride/<?= htmlspecialchars($product['image']) ?>"
        alt="<?= htmlspecialchars($product['name']) ?>"
        onerror="this.onerror=null;this.src='/ashoka-electroride/assets/images/no-image.png';"
      >
    </div>

    <!-- INFO CARD -->
    <div class="product-info-card">
      <h1><?= htmlspecialchars($product['name']) ?></h1>

      <p class="product-meta">
        Brand: <strong><?= htmlspecialchars($product['brand']) ?></strong>
        <span>|</span>
        Category: <?= htmlspecialchars($product['category']) ?>
      </p>

      <div class="product-price">
        ₹<?= number_format($product['price']) ?>
      </div>

      <p class="product-description">
        <?= nl2br(htmlspecialchars($product['description'] ?? '')) ?>
      </p>

      <div class="product-actions">

        <!-- ADD TO CART -->
        <form method="post" action="actions/cart-action.php">
          <input type="hidden" name="action" value="add">
          <input type="hidden" name="id" value="<?= $product['id'] ?>">
          <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">

          <button class="btn-add-cart">
            🛒 Add to Cart
          </button>
        </form>

        <!-- WHATSAPP -->
        <a
          class="btn-whatsapp"
          href="https://wa.me/919431492953?text=<?= urlencode('I am interested in ' . $product['name']) ?>"
          target="_blank"
        >
          💬 WhatsApp Enquiry
        </a>

      </div>
    </div>

  </div>
</div>

<?php include 'includes/footer.php'; ?>
