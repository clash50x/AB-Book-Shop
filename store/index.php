<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/header.php';
$cats = $conn->query("SELECT * FROM categories ORDER BY name LIMIT 12");
$featured = $conn->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 8");
$best = $conn->query("SELECT * FROM products ORDER BY id ASC LIMIT 4");
?>
<section class="hero">
  <div>
    <h1>Discover your next favorite book.</h1>
    <p>Novels, academic, entry-test, Islamic & programming books — delivered to your door with Cash on Delivery.</p>
    <a href="/store/shop.php" class="btn btn-primary">Browse the shop</a>
  </div>
  <div class="hero-art">📚</div>
</section>

<section class="section" id="categories">
  <div class="section-head"><h2>Shop by category</h2><a href="/store/shop.php">View all →</a></div>
  <div class="grid grid-cats">
  <?php while($c=$cats->fetch_assoc()): ?>
    <a class="cat-card" href="/store/shop.php?category=<?= (int)$c['id'] ?>">
      <?php if($c['logo']): ?><img src="<?= e($c['logo']) ?>" alt="<?= e($c['name']) ?>" loading="lazy"><?php endif; ?>
      <span><?= e($c['name']) ?></span>
    </a>
  <?php endwhile; ?>
  </div>
</section>

<section class="section">
  <div class="section-head"><h2>Featured books</h2><a href="/store/shop.php">See more →</a></div>
  <div class="grid grid-products">
  <?php while($p=$featured->fetch_assoc()): ?>
    <a class="card" href="/store/product.php?id=<?= (int)$p['id'] ?>">
      <div class="card-img"><?php if($p['image']): ?><img src="<?= e($p['image']) ?>" alt="<?= e($p['title']) ?>" loading="lazy"><?php endif; ?></div>
      <div class="card-body">
        <div class="card-title"><?= e($p['title']) ?></div>
        <div class="card-price"><?= money($p['price']) ?></div>
      </div>
    </a>
  <?php endwhile; ?>
  </div>
</section>

<section class="section">
  <div class="section-head"><h2>Best sellers</h2></div>
  <div class="grid grid-products">
  <?php while($p=$best->fetch_assoc()): ?>
    <a class="card" href="/store/product.php?id=<?= (int)$p['id'] ?>">
      <div class="card-img"><?php if($p['image']): ?><img src="<?= e($p['image']) ?>" alt="<?= e($p['title']) ?>" loading="lazy"><?php endif; ?></div>
      <div class="card-body">
        <div class="card-title"><?= e($p['title']) ?></div>
        <div class="card-price"><?= money($p['price']) ?></div>
      </div>
    </a>
  <?php endwhile; ?>
  </div>
</section>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
