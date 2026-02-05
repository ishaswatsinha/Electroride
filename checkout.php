<?php
require_once 'includes/auth.php';

session_start();
include 'includes/header.php';


$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
  header("Location: cart.php");
  exit;
}

$total = 0;
?>

<div class="container checkout-page my-5">

  <h2 class="checkout-title mb-4 animate-fade">
    🧾 Checkout
  </h2>

  <div class="row g-4">

    <!-- =====================
         LEFT: CUSTOMER FORM
    ====================== -->
    <div class="col-lg-7 animate-slide-left">

      <div class="checkout-card">
        <h5 class="mb-3">Customer Details</h5>

        <form method="post" action="place-order.php">

          <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <input type="tel" name="phone" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Email (optional)</label>
            <input type="email" name="email" class="form-control">
          </div>

          <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="address"
                      class="form-control"
                      rows="3"
                      required></textarea>
          </div>

          <button class="btn btn-primary w-100 checkout-btn mt-3">
            Place Order & WhatsApp →
          </button>

        </form>
      </div>

    </div>

    <!-- =====================
         RIGHT: ORDER SUMMARY
    ====================== -->
    <div class="col-lg-5 animate-slide-right">

      <div class="checkout-card checkout-summary">

        <h5 class="mb-3">Order Summary</h5>

        <?php foreach ($cart as $item):
          $subtotal = $item['price'] * $item['qty'];
          $total += $subtotal;
        ?>
          <div class="summary-item">
            <div>
              <?= htmlspecialchars($item['name']) ?>
              <small class="text-muted">×<?= $item['qty'] ?></small>
            </div>
            <strong>₹<?= number_format($subtotal) ?></strong>
          </div>
        <?php endforeach; ?>

        <hr>

        <div class="summary-total">
          <span>Total</span>
          <strong>₹<?= number_format($total) ?></strong>
        </div>

      </div>

    </div>

  </div>
</div>

<?php include 'includes/footer.php'; ?>
