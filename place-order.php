<?php
require_once 'includes/auth.php';

session_start();
require_once 'config/database.php';

$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
  header("Location: cart.php");
  exit;
}

/* =========================
   VALIDATE INPUT
========================= */
$name    = trim($_POST['name'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$email   = trim($_POST['email'] ?? '');
$address = trim($_POST['address'] ?? '');

if (!$name || !$phone || !$address) {
  die("Invalid request");
}

/* =========================
   CALCULATE TOTAL
========================= */
$total = 0;
foreach ($cart as $item) {
  $total += $item['price'] * $item['qty'];
}

/* =========================
   SAVE ORDER
========================= */
$stmt = mysqli_prepare(
  $conn,
  "INSERT INTO orders (customer_name, phone, email, address, total_amount)
   VALUES (?, ?, ?, ?, ?)"
);
mysqli_stmt_bind_param($stmt, "ssssi", $name, $phone, $email, $address, $total);
mysqli_stmt_execute($stmt);

$orderId = mysqli_insert_id($conn);

/* =========================
   SAVE ORDER ITEMS
========================= */
$itemStmt = mysqli_prepare(
  $conn,
  "INSERT INTO order_items
   (order_id, product_id, product_name, price, qty)
   VALUES (?, ?, ?, ?, ?)"
);

foreach ($cart as $item) {
  mysqli_stmt_bind_param(
    $itemStmt,
    "iisii",
    $orderId,
    $item['id'],
    $item['name'],
    $item['price'],
    $item['qty']
  );
  mysqli_stmt_execute($itemStmt);
}

/* =========================
   BUILD WHATSAPP MESSAGE
========================= */

$message  = "New Order from Ashoka EV\n\n";
$message .= "Order ID: #{$orderId}\n";
$message .= "Name: {$name}\n";
$message .= "Phone: {$phone}\n";
$message .= "Address: {$address}\n\n";
$message .= "Items:\n";

foreach ($cart as $item) {
  $message .= "- {$item['name']} ({$item['qty']}) × ₹{$item['price']}\n";
}

$message .= "\nTotal: ₹{$total}";

/* URL ENCODE ONCE */
$encodedMessage = urlencode($message);

/* =========================
   CLEAR CART
========================= */
unset($_SESSION['cart']);

/* =========================
   REDIRECT TO WHATSAPP
========================= */
$whatsappUrl = "https://wa.me/919431492953?text={$encodedMessage}";
$_SESSION['last_order'] = [
  'order_id' => $orderId,
  'whatsapp' => $whatsappUrl
];

header("Location: thank-you.php");
exit;
