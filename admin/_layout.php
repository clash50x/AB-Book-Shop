<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__.'/../includes/functions.php';
require_once __DIR__.'/../includes/auth.php';
require_admin();
$active = $active ?? '';
?><!doctype html><html><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= isset($pageTitle)?e($pageTitle).' — Admin':'AB Book Shop Admin' ?></title>
<link rel="stylesheet" href="/assets/css/style.css">
</head><body>
<header class="admin-nav">
  <div class="container nav-inner">
    <a href="/admin/index.php" class="brand">AB Book Shop · Admin</a>
    <nav>
      <a href="/admin/index.php" class="<?= $active==='dash'?'active':'' ?>">Dashboard</a>
      <a href="/admin/products.php" class="<?= $active==='products'?'active':'' ?>">Products</a>
      <a href="/admin/categories.php" class="<?= $active==='categories'?'active':'' ?>">Categories</a>
      <a href="/admin/orders.php" class="<?= $active==='orders'?'active':'' ?>">Orders</a>
      <a href="/admin/users.php" class="<?= $active==='users'?'active':'' ?>">Users</a>
      <a href="/admin/index.php?action=logout">Logout</a>
    </nav>
  </div>
</header>
<main class="container" style="padding-top:24px">
<?php if($m=flash_get('msg')): ?><div class="toast"><?= e($m) ?></div><?php endif; ?>
<?php if($m=flash_get('err')): ?><div class="toast toast-err"><?= e($m) ?></div><?php endif; ?>
