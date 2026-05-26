<?php
require_once __DIR__.'/../includes/db.php';
$active='orders'; $pageTitle='Orders';
require_once __DIR__.'/_layout.php';
$rs=$conn->query("SELECT o.*, u.name, u.email, a.city FROM orders o JOIN users u ON u.id=o.user_id JOIN addresses a ON a.id=o.address_id ORDER BY o.id DESC");
?>
<h2>Orders</h2>
<div class="panel">
<table class="table"><thead><tr><th>#</th><th>Customer</th><th>City</th><th>Total</th><th>Status</th><th>Date</th><th>Update</th></tr></thead><tbody>
<?php while($o=$rs->fetch_assoc()):
  $oid=(int)$o['id'];
  $it=$conn->query("SELECT oi.*, p.title FROM order_items oi JOIN products p ON p.id=oi.product_id WHERE oi.order_id=$oid"); ?>
  <tr>
    <td>
      #<?= $oid ?>
      <details style="margin-top:6px"><summary style="cursor:pointer;color:var(--primary);font-size:.85rem">Items</summary>
        <ul style="margin:6px 0 0;padding-left:18px;font-size:.85rem">
        <?php while($i=$it->fetch_assoc()): ?><li><?= e($i['title']) ?> × <?= (int)$i['quantity'] ?> — <?= money($i['price']*$i['quantity']) ?></li><?php endwhile; ?>
        </ul>
      </details>
    </td>
    <td><?= e($o['name']) ?><br><span class="muted" style="font-size:.8rem"><?= e($o['email']) ?></span></td>
    <td><?= e($o['city']) ?></td>
    <td><?= money($o['total_price']) ?></td>
    <td><span class="status <?= e($o['status']) ?>"><?= e($o['status']) ?></span></td>
    <td><?= e(date('M j, Y',strtotime($o['created_at']))) ?></td>
    <td>
      <form method="post" action="/api/update_order.php" style="display:flex;gap:6px">
        <input type="hidden" name="id" value="<?= $oid ?>">
        <select name="status" class="input" style="padding:6px">
          <?php foreach(['Pending','Confirmed','Shipped','Delivered'] as $s): ?>
            <option value="<?= $s ?>" <?= $o['status']===$s?'selected':'' ?>><?= $s ?></option>
          <?php endforeach; ?>
        </select>
        <button class="btn btn-outline" style="padding:6px 12px">Save</button>
      </form>
    </td>
  </tr>
<?php endwhile; ?>
</tbody></table>
</div>
</main></body></html>
