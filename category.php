<?php
require_once __DIR__ . '/config/database.php';

/* -------------------------------
   READ FILTERS
-------------------------------- */
$main  = $_GET['main']  ?? 'bicycle';
$type  = $_GET['type']  ?? null;
$brand = $_GET['brand'] ?? null;
$sort  = $_GET['sort']  ?? null;

/* -------------------------------
   GET MAIN CATEGORY
-------------------------------- */
$stmt = mysqli_prepare(
  $conn,
  "SELECT id, name FROM main_categories WHERE slug = ? AND status = 1"
);
mysqli_stmt_bind_param($stmt, "s", $main);
mysqli_stmt_execute($stmt);
$mainRes = mysqli_stmt_get_result($stmt);
$mainCategory = mysqli_fetch_assoc($mainRes);

if (!$mainCategory) die("Invalid category");

/* -------------------------------
   PRODUCT QUERY
-------------------------------- */
$sql = "
SELECT DISTINCT p.id, p.name, p.price, p.image
FROM products p
JOIN categories c ON p.category_id = c.id
JOIN brands b ON p.brand_id = b.id
JOIN main_categories m ON c.main_category_id = m.id
WHERE m.slug = ?
AND p.status = 1
";

$params = [$main];
$types  = "s";

if ($type) {
  $sql .= " AND c.slug = ?";
  $params[] = $type;
  $types .= "s";
}

if ($brand) {
  $sql .= " AND b.slug = ?";
  $params[] = $brand;
  $types .= "s";
}

/* -------------------------------
   SORTING
-------------------------------- */
switch ($sort) {
  case 'price_low':
    $sql .= " ORDER BY p.price ASC";
    break;
  case 'price_high':
    $sql .= " ORDER BY p.price DESC";
    break;
  case 'name_asc':
    $sql .= " ORDER BY p.name ASC";
    break;
  case 'name_desc':
    $sql .= " ORDER BY p.name DESC";
    break;
  default:
    $sql .= " ORDER BY p.id DESC";
}

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$hasSidebar = 'has-sidebar';
include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="ae-sidebar-overlay"></div>

<main class="ae-main">
<section class="ev-shop">

  <!-- HEADER -->
  <div class="ev-shop-header">
    <h2><?= htmlspecialchars($mainCategory['name']) ?></h2>

    <!-- SORT DROPDOWN -->
    <form method="get">
      <input type="hidden" name="main" value="<?= $main ?>">
      <?php if ($type): ?><input type="hidden" name="type" value="<?= $type ?>"><?php endif; ?>
      <?php if ($brand): ?><input type="hidden" name="brand" value="<?= $brand ?>"><?php endif; ?>

      <select name="sort" onchange="this.form.submit()" class="form-select ev-price-filter">
        <option value="">Sort Products</option>
        <option value="price_low"  <?= $sort=='price_low'?'selected':'' ?>>Price: Low → High</option>
        <option value="price_high" <?= $sort=='price_high'?'selected':'' ?>>Price: High → Low</option>
        <option value="name_asc"   <?= $sort=='name_asc'?'selected':'' ?>>Name: A → Z</option>
        <option value="name_desc"  <?= $sort=='name_desc'?'selected':'' ?>>Name: Z → A</option>
      </select>
    </form>
  </div>

  <!-- PRODUCTS -->
  <div class="ev-grid">
    <?php if (mysqli_num_rows($result)): ?>
      <?php while ($p = mysqli_fetch_assoc($result)): ?>
        <div class="ev-card">
          <a href="product.php?id=<?= $p['id'] ?>">
            <img src="<?= $p['image'] ?>" alt="<?= htmlspecialchars($p['name']) ?>">
          </a>

          <h5><?= htmlspecialchars($p['name']) ?></h5>
          <p>₹<?= number_format($p['price']) ?></p>

          <form method="post" action="actions/cart-action.php">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="id" value="<?= $p['id'] ?>">
            <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
            <button class="btn btn-primary w-100">Add to cart</button>
          </form>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No products found.</p>
    <?php endif; ?>
  </div>

</section>
</main>

<?php include 'includes/footer.php'; ?>
