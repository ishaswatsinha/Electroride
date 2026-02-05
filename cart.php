<?php
require_once 'includes/auth.php';

session_start();
include 'includes/header.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>

<div class="container cart-page">

  <h2 class="cart-title mb-4">🛒 Your Shopping Cart</h2>

  <?php if (empty($cart)): ?>

    <!-- EMPTY CART -->
    <div class="cart-empty text-center py-5">
      <i class="fas fa-shopping-cart fa-4x mb-3"></i>
      <h4>Your cart is empty</h4>
      <p class="text-muted">Looks like you haven’t added anything yet.</p>
      <a href="index.php" class="btn btn-primary mt-3">
        Continue Shopping
      </a>
    </div>

  <?php else: ?>

    <div class="cart-wrapper">

      <?php foreach ($cart as $item):
        $subtotal = $item['price'] * $item['qty'];
        $total += $subtotal;
        ?>

        <div class="cart-item animate-fade" data-id="<?= $item['id'] ?>">

          <!-- IMAGE -->
          <div class="cart-img">
            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
          </div>

          <!-- INFO -->
          <div class="cart-info">
            <h5><?= htmlspecialchars($item['name']) ?></h5>
            <p class="price">₹<?= number_format($item['price']) ?></p>

            <div class="qty-box">
              <button class="qty-btn ajax-btn" data-action="minus" data-id="<?= $item['id'] ?>">−</button>

              <span class="qty-text"><?= $item['qty'] ?></span>

              <button class="qty-btn ajax-btn" data-action="plus" data-id="<?= $item['id'] ?>">+</button>
            </div>
          </div>

          <!-- TOTAL -->
          <div class="cart-total">
            <span>₹<?= number_format($subtotal) ?></span>

            <button class="remove-btn ajax-btn" data-action="remove" data-id="<?= $item['id'] ?>">
              <i class="fas fa-trash"></i>
            </button>
          </div>

        </div>

      <?php endforeach; ?>

    </div>

    <!-- SUMMARY -->
    <div class="cart-summary animate-slide-up">
      <div class="summary-row">
        <span>Total Amount</span>
        <strong>
          ₹<span id="cartTotal"><?= number_format($total) ?></span>
        </strong>
      </div>

      <button class="btn-primary checkout-btn w-100 mt-4  ">
        <a class="text-decoration-none h3" href="checkout.php">Proceed to Checkout →</a>
      </button>
    </div>

  <?php endif; ?>

</div>

<!-- =============================
     AJAX CART SCRIPT (FIXED)
============================= -->
<script>
  const cartLocks = {}; // per-item lock

  document.addEventListener("click", function (e) {

    const btn = e.target.closest(".ajax-btn");
    if (!btn) return;

    const id = btn.dataset.id;
    const action = btn.dataset.action;

    if (cartLocks[id]) return; // 🔒 lock per item
    cartLocks[id] = true;

    const row = btn.closest(".cart-item");
    const originalHTML = btn.innerHTML;

    // 🔄 loader
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled = true;

    fetch("actions/cart-ajax.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `id=${encodeURIComponent(id)}&action=${encodeURIComponent(action)}`
    })
      .then(res => res.json())
      .then(data => {

        if (!data.success) return;

        /* CART BADGE */
        const badge = document.querySelector(".cart-count");
        if (badge) {
          badge.textContent = data.cartCount;
          badge.style.display = data.cartCount > 0 ? "inline-block" : "none";
        }

        /* TOTAL PRICE */
        animateNumber(
          document.getElementById("cartTotal"),
          data.totalPrice
        );

        /* UPDATE CURRENT ROW ONLY */
        const updatedItem = data.cart.find(i => i.id == id);

        if (!updatedItem) {
          row.classList.add("remove-anim");
          setTimeout(() => row.remove(), 300);
        } else {
          row.querySelector(".qty-text").textContent = updatedItem.qty;
        }

        if (data.cart.length === 0) location.reload();

      })
      .catch(err => console.error(err))
      .finally(() => {
        btn.innerHTML = originalHTML;
        btn.disabled = false;
        cartLocks[id] = false;
      });
  });

  /* PRICE ANIMATION */
  function animateNumber(el, newValue) {
    if (!el) return;
    el.classList.add("price-pulse");
    el.textContent = newValue;
    setTimeout(() => el.classList.remove("price-pulse"), 300);
  }
</script>

<?php include 'includes/footer.php'; ?>