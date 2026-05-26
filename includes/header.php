<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/auth.php';
$BASE = '/';
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title><?= isset($pageTitle) ? e($pageTitle).' — AB Book Shop' : 'AB Book Shop — Buy Books Online with Cash on Delivery' ?></title>
<meta name="description" content="AB Book Shop — buy novels, academic, entry-test, Islamic and programming books online with Cash on Delivery." />
<link rel="stylesheet" href="/assets/css/style.css" />
</head>
<body>
<header class="nav">
  <div class="container nav-inner">
    <a href="/store/index.php" class="brand">AB Book Shop</a>
    <button class="menu-btn" id="menuBtn" aria-label="Menu">☰</button>
    <nav class="menu" id="menu">
      <a href="/store/index.php">Home</a>
      <a href="/store/shop.php">Shop</a>
      <a href="/store/shop.php#categories">Categories</a>
      <a href="/store/cart.php" class="cart-link">Cart <span class="badge"><?= cart_count() ?></span></a>
      <?php if(is_logged_in()): ?>
        <a href="/store/dashboard.php"><?= e(current_user()['name']) ?></a>
        <a href="/store/logout.php">Logout</a>
      <?php else: ?>
        <a href="/store/login.php">Login</a>
        <a href="/store/signup.php" class="btn-primary-sm">Sign up</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container">
<?php if($m = flash_get('msg')): ?><div class="toast"><?= e($m) ?></div><?php endif; ?>
<?php if($m = flash_get('err')): ?><div class="toast toast-err"><?= e($m) ?></div><?php endif; ?>
