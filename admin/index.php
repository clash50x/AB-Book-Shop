<?php
// 1. Initialize the session if it hasn't been started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Intercept the logout parameter BEFORE any files or HTML render
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = array(); 
    session_destroy();   
    header("Location: /store/index.php"); // Redirect straight to storefront
    exit;
}

// 3. Keep your existing logic running seamlessly right below
require_once __DIR__.'/../includes/db.php';
$active='dash'; $pageTitle='Dashboard';
require_once __DIR__.'/_layout.php';

$stats=[
  'products'=>$conn->query("SELECT COUNT(*) c FROM products")->fetch_assoc()['c'],
  'categories'=>$conn->query("SELECT COUNT(*) c FROM categories")->fetch_assoc()['c'],
  'orders'=>$conn->query("SELECT COUNT(*) c FROM orders")->fetch_assoc()['c'],
  'users'=>$conn->query("SELECT COUNT(*) c FROM users WHERE role='user'")->fetch_assoc()['c'],
  'revenue'=>$conn->query("SELECT COALESCE(SUM(total_price),0) s FROM orders WHERE status='Delivered'")->fetch_assoc()['s'],
];
$recent=$conn->query("SELECT o.*, u.name FROM orders o JOIN users u ON u.id=o.user_id ORDER BY o.id DESC LIMIT 10");
?>
<h2>Dashboard</h2>
<div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(180px,1fr));margin-bottom:30px">
  <?php foreach([['Products',$stats['products']],['Categories',$stats['categories']],['Orders',$stats['orders']],['Users',$stats['users']],['Revenue (Delivered)',money($stats['revenue'])]] as [$l,$v]): ?>
    <div class="panel"><p class="muted" style="margin:0"><?= e($l) ?></p><h2 style="margin:6px 0 0;font-size:1.6rem"><?= e($v) ?></h2></div>
  <?php endforeach; ?>
</div>
<div class="panel">
  <h3>Recent orders</h3>
  <table class="table"><thead><tr><th>#</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr></thead><tbody>
  <?php while($o=$recent->fetch_assoc()): ?>
    <tr><td>#<?= (int)$o['id'] ?></td><td><?= e($o['name']) ?></td><td><?= money($o['total_price']) ?></td><td><span class="status <?= e($o['status']) ?>"><?= e($o['status']) ?></span></td><td><?= e(date('M j, Y',strtotime($o['created_at']))) ?></td></tr>
  <?php endwhile; ?>
  </tbody></table>
</div>
</main></body></html>