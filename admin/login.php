<?php
session_start();
require_once '../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare(
        $conn,
        "SELECT id, name, email, password FROM admins WHERE email = ? LIMIT 1"
    );
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($admin = mysqli_fetch_assoc($result)) {

        // ✅ CORRECT PASSWORD CHECK
        if (password_verify($password, $admin['password'])) {

            $_SESSION['admin_id']   = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];

            header("Location: dashboard.php");
            exit;

        } else {
            $error = "Invalid login details";
        }

    } else {
        $error = "Invalid login details";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login | Ashoka EV</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="admin-login">

<div class="login-wrapper">
  <div class="login-card animate-fade">

    <div class="login-header">
      <i class="fa-solid fa-user-shield"></i>
      <h2>Admin Login</h2>
      <p>Ashoka Electroride Panel</p>
    </div>

    <?php if (!empty($error)): ?>
      <div class="login-error animate-shake">
        <?= $error ?>
      </div>
    <?php endif; ?>

    <form method="post" class="login-form">

      <div class="input-group">
        <i class="fa-solid fa-envelope"></i>
        <input type="email" name="email" placeholder="Email address" required>
      </div>

      <div class="input-group">
        <i class="fa-solid fa-lock"></i>
        <input type="password" name="password" placeholder="Password" required>
      </div>

      <button type="submit" class="login-btn">
        Login →
      </button>

    </form>

  </div>
</div>

</body>
</html>
