<?php
require_once __DIR__.'/../includes/db.php';
$pageTitle='Shop';
require_once __DIR__.'/../includes/header.php';
$q = trim($_GET['q'] ?? '');
$cat = (int)($_GET['category'] ?? 0);
$where=[]; $params=[]; $types='';
if($q!==''){ $where[]='p.title LIKE ?'; $params[]='%'.$q.'%'; $types.='s'; }
if($cat>0){ $where[]='p.category_id = ?'; $params[]=$cat; $types.='i'; }
$sql='SELECT p.*, c.name AS cname FROM products p LEFT JOIN categories c ON c.id=p.category_id';
if($where) $sql.=' WHERE '.implode(' AND ',$where);
$sql.=' ORDER BY p.created_at DESC';
$stmt=$conn->prepare($sql);
if($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$rs=$stmt->get_result();
$cats=$conn->query("SELECT * FROM categories ORDER BY name");
?>
<section class="section">
  <h2>Browse books</h2>
  <form method="get" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:24px">
    <input class="input" name="q" placeholder="Search books..." value="<?= e($q) ?>" style="flex:1;min-width:200px">
    <select class="input" name="category" style="max-width:220px">
      <option value="0">All categories</option>
      <?php while($c=$cats->fetch_assoc()): ?>
        <option value="<?= (int)$c['id'] ?>" <?= $cat===(int)$c['id']?'selected':'' ?>><?= e($c['name']) ?></option>
      <?php endwhile; ?>
    </select>
    <button class="btn btn-primary">Search</button>
  </form>
  <div class="grid grid-products">
  <?php if($rs->num_rows===0): ?>
    <p class="muted">No books found.</p>
  <?php endif; while($p=$rs->fetch_assoc()): ?>
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
