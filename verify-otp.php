<?php
session_start();

include 'includes/header.php';
?>

<div class="otp-page">
  <div class="otp-card animate-otp">

    <?php if (!empty($_SESSION['otp_success'])): ?>

      <div class="otp-success">
        <div class="checkmark">
          ✓
        </div>
        <h3>Verification Successful</h3>
        <p>Redirecting to login...</p>
      </div>

      <script>
        setTimeout(() => {
          window.location.href = "login.php?verified=1";
        }, 1500);
      </script>

      <?php unset($_SESSION['otp_success']); ?>

    <?php elseif (!isset($_SESSION['signup_data'])): ?>

      <?php header("Location: signup.php"); exit; ?>

    <?php else: ?>

      <div class="otp-icon">
        <i class="fa-solid fa-envelope-open-text"></i>
      </div>

      <h3>Email Verification</h3>
      <p class="otp-subtext">
        Enter the OTP sent to<br>
        <strong><?= htmlspecialchars($_SESSION['signup_data']['email']) ?></strong>
      </p>

      <?php if (!empty($_SESSION['otp_error'])): ?>
        <div class="otp-error shake">
          <?= $_SESSION['otp_error']; unset($_SESSION['otp_error']); ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($_SESSION['otp_info'])): ?>
        <div class="otp-info">
          <?= $_SESSION['otp_info']; unset($_SESSION['otp_info']); ?>
        </div>
      <?php endif; ?>

      <form method="post" action="verify-otp-save.php">
        <input type="text" name="otp" class="otp-input"
               maxlength="6" required autofocus
               inputmode="numeric" pattern="[0-9]*">
        <button class="otp-btn">Verify OTP</button>
      </form>

      <div class="otp-resend">
        <span id="otp-timer">Resend available in 60s</span><br>
        <a href="resend-otp.php" id="resend-link" style="display:none;">Resend OTP</a>
      </div>

      <script>
        let t = 60;
        const timer = setInterval(() => {
          document.getElementById('otp-timer').textContent =
            `Resend available in ${--t}s`;
          if (t <= 0) {
            clearInterval(timer);
            document.getElementById('otp-timer').style.display = 'none';
            document.getElementById('resend-link').style.display = 'inline';
          }
        }, 1000);
      </script>

    <?php endif; ?>

  </div>
</div>

<?php include 'includes/footer.php'; ?>
