<?php
require_once __DIR__ . '/../config/database.php';

$main  = $_GET['main']  ?? null;
$type  = $_GET['type']  ?? null;
$brand = $_GET['brand'] ?? null;
$sort  = $_GET['sort']  ?? null;

if (!$main) return;

/* TYPES */
$typeStmt = mysqli_prepare(
  $conn,
  "SELECT name, slug FROM categories
   WHERE main_category_id = (SELECT id FROM main_categories WHERE slug=?)
   AND status=1 ORDER BY name"
);
mysqli_stmt_bind_param($typeStmt, "s", $main);
mysqli_stmt_execute($typeStmt);
$typeResult = mysqli_stmt_get_result($typeStmt);

/* BRANDS */
$brandStmt = mysqli_prepare(
  $conn,
  "SELECT name, slug FROM brands
   WHERE main_category_id = (SELECT id FROM main_categories WHERE slug=?)
   AND status=1 ORDER BY name"
);
mysqli_stmt_bind_param($brandStmt, "s", $main);
mysqli_stmt_execute($brandStmt);
$brandResult = mysqli_stmt_get_result($brandStmt);

/* BUILD URL */
function buildUrl($params) {
  return 'category.php?' . http_build_query(array_filter($params));
}
?>

<aside class="ae-sidebar">
<ul class="ae-menu">

<!-- TYPE -->
<?php if (mysqli_num_rows($typeResult)): ?>
<li class="ae-item">
  <button class="ae-toggle">Type <i class="fa-solid fa-chevron-down"></i></button>
  <ul class="ae-submenu">
    <?php while ($t = mysqli_fetch_assoc($typeResult)): ?>
      <li>
        <a href="<?= buildUrl([
          'main'=>$main,
          'type'=>$t['slug'],
          'brand'=>$brand,
          'sort'=>$sort
        ]) ?>">
          <?= strtoupper($t['name']) ?>
        </a>
      </li>
    <?php endwhile; ?>
  </ul>
</li>
<?php endif; ?>

<!-- BRAND -->
<?php if (mysqli_num_rows($brandResult)): ?>
<li class="ae-item">
  <button class="ae-toggle">Brand <i class="fa-solid fa-chevron-down"></i></button>
  <ul class="ae-submenu">
    <?php while ($b = mysqli_fetch_assoc($brandResult)): ?>
      <li>
        <a href="<?= buildUrl([
          'main'=>$main,
          'type'=>$type,
          'brand'=>$b['slug'],
          'sort'=>$sort
        ]) ?>">
          <?= strtoupper($b['name']) ?>
        </a>
      </li>
    <?php endwhile; ?>
  </ul>
</li>
<?php endif; ?>

</ul>
</aside>
