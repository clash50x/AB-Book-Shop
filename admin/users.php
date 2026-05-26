<?php
require_once __DIR__.'/../includes/db.php';
$active='users'; $pageTitle='Users';
require_once __DIR__.'/_layout.php';
$rs=$conn->query("SELECT id,name,email,role,created_at FROM users ORDER BY id DESC");
?>
<h2>Users</h2>
<div class="panel">
<table class="table"><thead><tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Joined</th></tr></thead><tbody>
<?php while($u=$rs->fetch_assoc()): ?>
  <tr><td>#<?= (int)$u['id'] ?></td><td><?= e($u['name']) ?></td><td><?= e($u['email']) ?></td><td><?= e($u['role']) ?></td><td><?= e(date('M j, Y',strtotime($u['created_at']))) ?></td></tr>
<?php endwhile; ?>
</tbody></table>
</div>
</main></body></html>
