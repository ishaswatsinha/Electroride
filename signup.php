<?php include 'includes/header.php'; ?>

<div class="signup-wrapper">
  <div class="signup-container">

    <div class="signup-left">
      <h2>Sign up</h2>
      <p class="subtitle">Create your account</p>

      <form method="post" action="signup-save.php">

        <label>Name</label>
        <input type="text" name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Mobile Number</label>
        <input type="text" name="mobile" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn-primary">Create account</button>

        <p class="login-link">
          Already have an account? <a href="login.php">Log in</a>
        </p>
      </form>
    </div>

    <div class="signup-right"></div>

  </div>
</div>

<?php include 'includes/footer.php'; ?>
