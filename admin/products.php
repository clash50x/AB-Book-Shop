<?php
require_once __DIR__.'/../includes/db.php';
$active='products'; $pageTitle='Products';
require_once __DIR__.'/_layout.php';
$cats=$conn->query("SELECT * FROM categories ORDER BY name");
$catList=[]; while($c=$cats->fetch_assoc()) $catList[]=$c;
$edit=null;
if(isset($_GET['edit'])){
  $s=$conn->prepare("SELECT * FROM products WHERE id=?"); $s->bind_param('i',$_GET['edit']); $s->execute();
  $edit=$s->get_result()->fetch_assoc();
}
$rs=$conn->query("SELECT p.*, c.name cname FROM products p LEFT JOIN categories c ON c.id=p.category_id ORDER BY p.id DESC");
?>
<h2>Products</h2>
<div class="dash" style="grid-template-columns:1fr 1.4fr">
  <div class="panel">
    <h3><?= $edit?'Edit product':'Add product' ?></h3>
    <form method="post" action="<?= $edit?'/api/update_product.php':'/api/add_product.php' ?>">
      <?php if($edit): ?><input type="hidden" name="id" value="<?= (int)$edit['id'] ?>"><?php endif; ?>
      <div class="field"><label>Title</label><input class="input" name="title" required value="<?= e($edit['title']??'') ?>"></div>
      <div class="field"><label>Description</label><textarea class="input" name="description" rows="3"><?= e($edit['description']??'') ?></textarea></div>
      <div class="field"><label>Price (Rs)</label><input class="input" type="number" step="1" min="0" name="price" required value="<?= e($edit['price']??'') ?>"></div>
      <div class="field"><label>Image URL</label><input class="input" name="image" value="<?= e($edit['image']??'') ?>"></div>
      <div class="field"><label>Category</label><select class="input" name="category_id" required>
        <option value="">— select —</option>
        <?php foreach($catList as $c): ?><option value="<?= (int)$c['id'] ?>" <?= ($edit&&$edit['category_id']==$c['id'])?'selected':'' ?>><?= e($c['name']) ?></option><?php endforeach; ?>
      </select></div>
      <button class="btn btn-primary btn-block"><?= $edit?'Update':'Add product' ?></button>
      <?php if($edit): ?><a href="/admin/products.php" class="btn btn-outline btn-block" style="margin-top:8px">Cancel edit</a><?php endif; ?>
    </form>
  </div>
  <div class="panel">
    <h3>All products</h3>
    <table class="table"><thead><tr><th></th><th>Title</th><th>Category</th><th>Price</th><th></th></tr></thead><tbody>
    <?php while($p=$rs->fetch_assoc()): ?>
      <tr>
        <td><?php if($p['image']): ?><img src="<?= e($p['image']) ?>" style="width:40px;height:54px;object-fit:cover;border-radius:4px"><?php endif; ?></td>
        <td><?= e($p['title']) ?></td><td><?= e($p['cname']) ?></td><td><?= money($p['price']) ?></td>
        <td>
          <a href="?edit=<?= (int)$p['id'] ?>" class="btn btn-outline" style="padding:6px 12px">Edit</a>
          <form method="post" action="/api/delete_product.php" style="display:inline" onsubmit="return confirm('Delete this product?')">
            <input type="hidden" name="id" value="<?= (int)$p['id'] ?>"><button class="btn btn-outline" style="padding:6px 12px">Delete</button>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody></table>
  </div>
</div>
</main></body></html>
