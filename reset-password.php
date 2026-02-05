<?php
session_start();
if (!isset($_SESSION['reset_data'])) {
  header("Location: forgot-password.php");
  exit;
}
include 'includes/header.php';
?>

<div class="otp-page">
  <div class="otp-card animate-otp">

    <div class="otp-icon">
      <i class="fa-solid fa-lock-open"></i>
    </div>

    <h3>Reset Password</h3>

    <?php if (!empty($_SESSION['fp_error'])): ?>
      <div class="otp-error shake">
        <?= $_SESSION['fp_error']; unset($_SESSION['fp_error']); ?>
      </div>
    <?php endif; ?>

    <form method="post" action="reset-password-save.php">

      <input type="text"
             name="otp"
             class="otp-input"
             placeholder="Enter OTP"
             maxlength="6"
             required>

      <input type="password"
             name="password"
             class="otp-input mt-3"
             placeholder="New Password"
             required>

      <button class="otp-btn">Update Password</button>
    </form>

  </div>
</div>

<?php include 'includes/footer.php'; ?>
