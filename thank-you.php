<?php
require_once 'includes/auth.php';

session_start();

if (!isset($_SESSION['last_order'])) {
  header("Location: index.php");
  exit;
}

$orderId = $_SESSION['last_order']['order_id'];
$whatsappUrl = $_SESSION['last_order']['whatsapp'];

/* Prevent re-opening */
unset($_SESSION['last_order']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Thank You | Ashoka EV</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="assets/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    .thank-you-box {
      max-width: 600px;
      margin: 100px auto;
      background: #fff;
      border-radius: 18px;
      padding: 40px;
      text-align: center;
      box-shadow: 0 20px 60px rgba(0,0,0,0.1);
      animation: fadeUp 0.6s ease;
    }

    .thank-you-box i {
      font-size: 64px;
      color: #28a745;
      margin-bottom: 20px;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>

<body>

<div class="thank-you-box">
  <i class="fas fa-check-circle"></i>

  <h2 class="mb-2">Thank You for Your Order!</h2>

  <p class="text-muted">
    Your order has been placed successfully.
  </p>

  <h5 class="mt-3">
    Order ID: <strong>#<?= htmlspecialchars($orderId) ?></strong>
  </h5>

  <p class="mt-3">
    We have opened WhatsApp so you can confirm your order with our team.
  </p>

  <div class="d-flex justify-content-center gap-3 mt-4">
    <a href="<?= htmlspecialchars($whatsappUrl) ?>"
       target="_blank"
       class="btn btn-success btn-lg">
      Open WhatsApp
    </a>

    <a href="index.php" class="btn btn-outline-primary btn-lg">
      Continue Shopping
    </a>
  </div>
</div>

<!-- AUTO OPEN WHATSAPP -->
<script>
  setTimeout(() => {
    window.open("<?= htmlspecialchars($whatsappUrl) ?>", "_blank");
  }, 800);
</script>

<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

</body>
</html>
