<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

/* Cart count */
$cartCount = 0;
if (!empty($_SESSION['cart'])) {
  foreach ($_SESSION['cart'] as $item) {
    $cartCount += $item['qty'];
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Ashoka Electroride</title>

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="assets/css/style.css">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@500;700&family=Roboto:wght@400;500;700&display=swap"
    rel="stylesheet">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

  <!-- Swiper -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
</head>

<body id="top" class="<?= $hasSidebar ?? '' ?>">

  <header class="header" data-header>
    <div class="container d-flex align-items-center justify-content-between">

      <!-- LOGO -->
      <a href="index.php" class="logo text-decoration-none">AshokaEV</a>

      <!-- <div class="h1">
        <?php if (!empty($_SESSION['user'])): ?>
          Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?>
        <?php endif; ?>
      </div> -->

      
      <!-- NAV -->
      <nav class="navbar" data-navbar>
        <ul class="navbar-list">

          <li><a href="index.php" class="navbar-link text-decoration-none" data-nav-link>Home</a></li>
          <li><a href="about.php" class="navbar-link text-decoration-none" data-nav-link>About</a></li>
          <li><a href="category.php?main=bicycle" class="navbar-link text-decoration-none" data-nav-link>Bicycle</a>
          </li>
          <li><a href="category.php?main=scooter" class="navbar-link text-decoration-none" data-nav-link>Scooter</a>
          </li>
          <li><a href="category.php?main=bike" class="navbar-link text-decoration-none" data-nav-link>Bike</a></li>
          <li><a href="category.php?main=3wheeler" class="navbar-link text-decoration-none" data-nav-link>3Wheeler</a>
          </li>
          <li><a href="contact.php" class="navbar-link text-decoration-none" data-nav-link>Contact Us</a></li>

          <!-- CART -->
          <li class="nav-cart">
            <a href="cart.php" class="navbar-link position-relative">
              <i class="fas fa-shopping-cart"></i>
              <?php if ($cartCount > 0): ?>
                <span class="cart-count"><?= $cartCount ?></span>
              <?php endif; ?>
            </a>
          </li>

          <!-- LOGIN -->
          <li>
            <?php if (!empty($_SESSION['user'])): ?>
              <a href="logout.php" class="button btn-red text-decoration-none">
                Logout
              </a>
            <?php else: ?>
              <a href="login.php" class="button btn-red text-decoration-none">
                Login
              </a>
            <?php endif; ?>
          </li>


        </ul>
      </nav>

      <!-- MOBILE TOGGLE -->
      <button type="button" class="nav-toggle-btn" aria-label="Toggle menu" data-nav-toggler>
        <ion-icon name="menu-outline" class="open"></ion-icon>
        <ion-icon name="close-outline" class="close"></ion-icon>
      </button>

    </div>

    <button class="ae-sidebar-toggle d-lg-none" aria-label="Toggle filters">
      <i class="fa-solid fa-filter"></i>
    </button>

  </header>