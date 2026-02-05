<?php
session_start();
include 'includes/header.php';
?>

<section class="login-bg d-flex align-items-center justify-content-center">
  <div class="login-glass">

    <div class="user-circle">
      <i class="fa-regular fa-user"></i>
    </div>

    <form class="login-form" method="post" action="login-save.php">

      <?php if (!empty($_SESSION['login_error'])): ?>
        <div class="otp-error mb-3">
          <?= $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
        </div>
      <?php endif; ?>

      <div class="input-group-custom">
        <i class="fa-regular fa-envelope"></i>
        <input type="email" name="email" placeholder="Email ID" required>
      </div>

      <div class="input-group-custom">
        <i class="fa-solid fa-lock"></i>
        <input type="password" name="password" placeholder="Password" required>
      </div>

      <div class="login-options">
        <a href="signup.php">Sign Up</a>
        <a href="forgot-password.php">Forgot Password?</a>
      </div>

      <button type="submit" class="login-btn">LOGIN</button>
    </form>

  </div>
</section>

<?php include 'includes/footer.php'; ?>
