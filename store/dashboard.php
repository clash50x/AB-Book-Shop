<?php
require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/header.php';
require_login('/store/login.php');
$uid=current_user()['id'];
$tab=$_GET['tab']??'orders';

// Handle address add/delete
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(($_POST['action']??'')==='add_address'){
    $fa=trim($_POST['full_address']??''); $ci=trim($_POST['city']??''); $ph=trim($_POST['phone']??'');
    if($fa&&$ci&&$ph){
      $s=$conn->prepare("INSERT INTO addresses(user_id,full_address,city,phone) VALUES(?,?,?,?)");
      $s->bind_param('isss',$uid,$fa,$ci,$ph); $s->execute();
      flash_set('msg','Address added');
    } else flash_set('err','Fill all fields');
    redirect('/store/dashboard.php?tab=addresses');
  }
  if(($_POST['action']??'')==='del_address'){
    $aid=(int)($_POST['id']??0);
    $s=$conn->prepare("DELETE FROM addresses WHERE id=? AND user_id=?");
    $s->bind_param('ii',$aid,$uid); $s->execute();
    flash_set('msg','Address deleted'); redirect('/store/dashboard.php?tab=addresses');
  }
}
?>
<div class="dash">
  <aside class="side">
    <h3 style="font-size:1rem;margin-bottom:10px">My account</h3>
    <a href="?tab=orders" class="<?= $tab==='orders'?'active':'' ?>">Orders</a>
    <a href="?tab=addresses" class="<?= $tab==='addresses'?'active':'' ?>">Addresses</a>
    <a href="?tab=profile" class="<?= $tab==='profile'?'active':'' ?>">Profile</a>
    <a href="/store/logout.php">Logout</a>
  </aside>
  <div class="panel">
  <?php if($tab==='orders'):
    $s=$conn->prepare("SELECT o.*, a.full_address, a.city FROM orders o JOIN addresses a ON a.id=o.address_id WHERE o.user_id=? ORDER BY o.id DESC");
    $s->bind_param('i',$uid); $s->execute(); $os=$s->get_result();
  ?>
    <h2>Order history</h2>
    <?php if($os->num_rows===0): ?><p class="muted">No orders yet.</p>
    <?php else: ?>
    <table class="table"><thead><tr><th>#</th><th>Date</th><th>Address</th><th>Total</th><th>Status</th><th></th></tr></thead><tbody>
    <?php while($o=$os->fetch_assoc()): ?>
      <tr><td>#<?= (int)$o['id'] ?></td><td><?= e(date('M j, Y',strtotime($o['created_at']))) ?></td><td><?= e($o['city']) ?></td><td><?= money($o['total_price']) ?></td><td><span class="status <?= e($o['status']) ?>"><?= e($o['status']) ?></span></td>
      <td><a href="?tab=order&id=<?= (int)$o['id'] ?>" style="color:var(--primary);font-weight:600">View</a></td></tr>
    <?php endwhile; ?>
    </tbody></table>
    <?php endif; ?>

  <?php elseif($tab==='order'):
    $oid=(int)($_GET['id']??0);
    $s=$conn->prepare("SELECT o.*, a.full_address, a.city, a.phone FROM orders o JOIN addresses a ON a.id=o.address_id WHERE o.id=? AND o.user_id=?");
    $s->bind_param('ii',$oid,$uid); $s->execute(); $o=$s->get_result()->fetch_assoc();
    if(!$o){ echo '<p>Order not found.</p>'; } else {
      $it=$conn->prepare("SELECT oi.*, p.title FROM order_items oi JOIN products p ON p.id=oi.product_id WHERE oi.order_id=?");
      $it->bind_param('i',$oid); $it->execute(); $items=$it->get_result();
  ?>
    <h2>Order #<?= (int)$o['id'] ?> <span class="status <?= e($o['status']) ?>" style="margin-left:8px"><?= e($o['status']) ?></span></h2>
    <p class="muted"><?= e(date('F j, Y g:i a',strtotime($o['created_at']))) ?></p>
    <p><strong>Delivery:</strong> <?= e($o['full_address']) ?>, <?= e($o['city']) ?> — <?= e($o['phone']) ?></p>
    <table class="table"><thead><tr><th>Book</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr></thead><tbody>
    <?php while($i=$items->fetch_assoc()): ?>
      <tr><td><?= e($i['title']) ?></td><td><?= (int)$i['quantity'] ?></td><td><?= money($i['price']) ?></td><td><?= money($i['price']*$i['quantity']) ?></td></tr>
    <?php endwhile; ?>
    </tbody></table>
    <p style="text-align:right;font-weight:700;color:var(--heading);margin-top:14px">Total: <?= money($o['total_price']) ?></p>
    <a href="?tab=orders" class="btn btn-outline">← Back to orders</a>
  <?php } elseif($tab==='addresses'):
    $s=$conn->prepare("SELECT * FROM addresses WHERE user_id=? ORDER BY id DESC");
    $s->bind_param('i',$uid); $s->execute(); $as=$s->get_result();
  ?>
    <h2>Saved addresses</h2>
    <?php while($a=$as->fetch_assoc()): ?>
      <div style="background:#F8FAFB;padding:14px;border-radius:10px;margin-bottom:10px;display:flex;justify-content:space-between;align-items:center">
        <div><?= e($a['full_address']) ?>, <?= e($a['city']) ?> — <?= e($a['phone']) ?></div>
        <form method="post" style="margin:0"><input type="hidden" name="action" value="del_address"><input type="hidden" name="id" value="<?= (int)$a['id'] ?>"><button class="btn btn-outline">Delete</button></form>
      </div>
    <?php endwhile; ?>
    <h3 style="margin-top:24px">Add new address</h3>
    <form method="post">
      <input type="hidden" name="action" value="add_address">
      <div class="field"><label>Full address</label><textarea class="input" name="full_address" rows="2" required></textarea></div>
      <div class="field"><label>City</label><input class="input" name="city" required></div>
      <div class="field"><label>Phone</label><input class="input" name="phone" required></div>
      <button class="btn btn-primary">Add address</button>
    </form>

  <?php elseif($tab==='profile'): $u=current_user(); ?>
    <h2>Profile</h2>
    <p><strong>Name:</strong> <?= e($u['name']) ?></p>
    <p><strong>Email:</strong> <?= e($u['email']) ?></p>
    <p><strong>Role:</strong> <?= e($u['role']) ?></p>
  <?php endif; ?>
  </div>
</div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
