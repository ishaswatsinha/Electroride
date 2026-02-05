<?php include 'includes/header.php'; ?>

<section class="login-bg d-flex align-items-center justify-content-center">
  <div class="login-glass">

    <div class="user-circle">
      <i class="fa-solid fa-key"></i>
    </div>

    <form method="post" action="forgot-password-save.php" class="login-form">

      <h4 class="text-center text-white mb-4">Forgot Password</h4>

      <?php if (!empty($_SESSION['fp_error'])): ?>
        <div class="otp-error">
          <?= $_SESSION['fp_error']; unset($_SESSION['fp_error']); ?>
        </div>
      <?php endif; ?>

      <div class="input-group-custom">
        <i class="fa-regular fa-envelope"></i>
        <input type="email" name="email" placeholder="Registered Email" required>
      </div>

      <button type="submit" class="login-btn">Send Reset Code</button>

      <div class="login-options mt-3">
        <a href="login.php">Back to Login</a>
      </div>

    </form>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
