<?php
session_start();

if ($_POST['action'] === 'fetch') {
  echo json_encode([
    'cart' => $_SESSION['cart'] ?? []
  ]);
  exit;
}


header('Content-Type: application/json');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_POST['action'] ?? '';
$id     = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if (!$id || !in_array($action, ['plus', 'minus', 'remove'], true)) {
    echo json_encode(['success' => false]);
    exit;
}

$found = false;

foreach ($_SESSION['cart'] as $key => &$item) {

    if ((int)$item['id'] !== $id) continue;

    $found = true;

    if ($action === 'plus') {
        $item['qty']++;
    }

    elseif ($action === 'minus') {
        $item['qty']--;
        if ($item['qty'] <= 0) {
            unset($_SESSION['cart'][$key]);
        }
    }

    elseif ($action === 'remove') {
        unset($_SESSION['cart'][$key]);
    }

    break;
}

/* 🔥 CRITICAL FIX */
unset($item);

/* Reindex */
$_SESSION['cart'] = array_values($_SESSION['cart']);

if (!$found) {
    echo json_encode(['success' => false]);
    exit;
}

/* Totals */
$totalQty = 0;
$totalPrice = 0;

foreach ($_SESSION['cart'] as $item) {
    $totalQty += $item['qty'];
    $totalPrice += $item['price'] * $item['qty'];
}

echo json_encode([
    'success'    => true,
    'cartCount' => $totalQty,
    'totalPrice'=> $totalPrice,
    'cart'      => $_SESSION['cart']
]);
exit;
