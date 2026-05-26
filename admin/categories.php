<?php
require_once __DIR__.'/../includes/db.php';
$active='categories'; $pageTitle='Categories';
require_once __DIR__.'/_layout.php';
$rs=$conn->query("SELECT * FROM categories ORDER BY name");
?>
<h2>Categories</h2>
<div class="dash" style="grid-template-columns:1fr 1.4fr">
  <div class="panel">
    <h3>Add category</h3>
    <form method="post" action="/api/add_category.php">
      <input type="hidden" name="action" value="add">
      <div class="field"><label>Name</label><input class="input" name="name" required></div>
      <div class="field"><label>Logo URL</label><input class="input" name="logo" placeholder="https://..."></div>
      <button class="btn btn-primary btn-block">Add category</button>
    </form>
  </div>
  <div class="panel">
    <h3>All categories</h3>
    <table class="table"><thead><tr><th>Logo</th><th>Name</th><th></th></tr></thead><tbody>
    <?php while($c=$rs->fetch_assoc()): ?>
      <tr>
        <td><?php if($c['logo']): ?><img src="<?= e($c['logo']) ?>" style="width:40px;height:40px;object-fit:contain"><?php endif; ?></td>
        <td>
          <form method="post" action="/api/add_category.php" style="display:flex;gap:8px;align-items:center">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
            <input class="input" name="name" value="<?= e($c['name']) ?>" style="max-width:160px">
            <input class="input" name="logo" value="<?= e($c['logo']) ?>" placeholder="Logo URL">
            <button class="btn btn-outline" style="padding:6px 12px">Save</button>
          </form>
        </td>
        <td>
          <form method="post" action="/api/add_category.php" onsubmit="return confirm('Delete category?')">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
            <button class="btn btn-outline" style="padding:6px 12px">Delete</button>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody></table>
  </div>
</div>
</main></body></html>
