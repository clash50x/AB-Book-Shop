<?php
require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/header.php';
$id=(int)($_GET['id']??0);
$stmt=$conn->prepare("SELECT p.*, c.name AS cname FROM products p LEFT JOIN categories c ON c.id=p.category_id WHERE p.id=?");
$stmt->bind_param('i',$id); $stmt->execute();
$p=$stmt->get_result()->fetch_assoc();
if(!$p){ echo '<div class="empty"><div class="icon">📕</div><h2>Book not found</h2><a class="btn btn-primary" href="/store/shop.php">Back to shop</a></div>'; require_once __DIR__.'/../includes/footer.php'; exit; }
?>
<section class="product-detail">
  <div class="img"><?php if($p['image']): ?><img src="<?= e($p['image']) ?>" alt="<?= e($p['title']) ?>"><?php endif; ?></div>
  <div>
    <p class="muted"><?= e($p['cname'] ?? 'Uncategorized') ?></p>
    <h1><?= e($p['title']) ?></h1>
    <div class="price"><?= money($p['price']) ?></div>
    <p><?= nl2br(e($p['description'])) ?></p>
    <form method="post" action="/store/cart.php" style="display:flex;gap:10px;align-items:center;margin-top:18px">
      <input type="hidden" name="action" value="add">
      <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
      <input class="qty-input" type="number" name="qty" value="1" min="1">
      <button class="btn btn-primary">Add to cart</button>
      <a href="/store/shop.php" class="btn btn-outline">Continue browsing</a>
    </form>
  </div>
</section>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
