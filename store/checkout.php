<?php
require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/header.php';
require_login('/store/login.php');
$items=cart();
if(empty($items)){ flash_set('err','Your cart is empty'); redirect('/store/cart.php'); }
$uid=current_user()['id'];
$ads=$conn->prepare("SELECT * FROM addresses WHERE user_id=? ORDER BY id DESC");
$ads->bind_param('i',$uid); $ads->execute(); $addresses=$ads->get_result();
$ids=implode(',',array_map('intval',array_keys($items)));
$rs=$conn->query("SELECT * FROM products WHERE id IN ($ids)");
$rows=[]; $total=0; while($r=$rs->fetch_assoc()){ $rows[$r['id']]=$r; $total += $r['price']*$items[$r['id']]; }
?>
<section class="section">
  <h2>Checkout</h2>
  <div style="display:grid;grid-template-columns:1.3fr 1fr;gap:30px" class="dash">
    <div class="panel">
      <h3>Delivery address</h3>
      <form method="post" action="/api/place_order.php">
        <?php if($addresses->num_rows>0): ?>
          <div class="field">
            <label>Use a saved address</label>
            <select name="address_id" class="input">
              <option value="">— Choose —</option>
              <?php while($a=$addresses->fetch_assoc()): ?>
                <option value="<?= (int)$a['id'] ?>"><?= e($a['full_address']) ?>, <?= e($a['city']) ?> (<?= e($a['phone']) ?>)</option>
              <?php endwhile; ?>
            </select>
          </div>
          <p class="muted center">— or add a new one —</p>
        <?php endif; ?>
        <div class="field"><label>Full address</label><textarea class="input" name="full_address" rows="2"></textarea></div>
        <div class="field"><label>City</label><input class="input" name="city"></div>
        <div class="field"><label>Phone</label><input class="input" name="phone"></div>
        <p class="muted">Payment method: <strong>Cash on Delivery</strong></p>
        <button class="btn btn-primary btn-block" style="margin-top:14px">Place order</button>
      </form>
    </div>
    <div class="panel">
      <h3>Order summary</h3>
      <?php foreach($items as $pid=>$qty): $r=$rows[$pid]??null; if(!$r) continue; ?>
        <div style="display:flex;justify-content:space-between;margin:8px 0;font-size:.94rem">
          <span><?= e($r['title']) ?> × <?= (int)$qty ?></span><span><?= money($r['price']*$qty) ?></span>
        </div>
      <?php endforeach; ?>
      <div style="border-top:1px solid var(--border);margin-top:12px;padding-top:12px;display:flex;justify-content:space-between;font-weight:700;color:var(--heading)">
        <span>Total</span><span><?= money($total) ?></span>
      </div>
    </div>
  </div>
</section>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
