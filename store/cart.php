<?php
require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/header.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $a=$_POST['action']??'';
  if($a==='add'){ cart_add($_POST['id']??0, $_POST['qty']??1); flash_set('msg','Added to cart'); redirect('/store/cart.php'); }
  if($a==='update'){ foreach(($_POST['qty']??[]) as $pid=>$q) cart_set($pid,$q); flash_set('msg','Cart updated'); redirect('/store/cart.php'); }
  if($a==='remove'){ cart_remove($_POST['id']??0); flash_set('msg','Removed'); redirect('/store/cart.php'); }
}
$items=cart();
?>
<section class="section">
  <h2>Your cart</h2>
  <?php if(empty($items)): ?>
    <div class="empty"><div class="icon">🛒</div><h3>Your cart is empty</h3><p class="muted">Find your next great read in the shop.</p><a href="/store/shop.php" class="btn btn-primary" style="margin-top:14px">Browse books</a></div>
  <?php else:
    $ids=implode(',',array_map('intval',array_keys($items)));
    $rs=$conn->query("SELECT * FROM products WHERE id IN ($ids)");
    $rows=[]; while($r=$rs->fetch_assoc()) $rows[$r['id']]=$r;
    $total=0;
  ?>
  <form method="post">
    <input type="hidden" name="action" value="update">
    <table class="cart-table"><thead><tr><th></th><th>Book</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th></tr></thead>
    <tbody>
    <?php foreach($items as $pid=>$qty): $r=$rows[$pid]??null; if(!$r) continue; $sub=$r['price']*$qty; $total+=$sub; ?>
      <tr>
        <td><?php if($r['image']): ?><img src="<?= e($r['image']) ?>" alt=""><?php endif; ?></td>
        <td><a href="/store/product.php?id=<?= (int)$r['id'] ?>" style="color:var(--heading);font-weight:600"><?= e($r['title']) ?></a></td>
        <td><?= money($r['price']) ?></td>
        <td><input class="qty-input" type="number" name="qty[<?= (int)$pid ?>]" value="<?= (int)$qty ?>" min="0"></td>
        <td><?= money($sub) ?></td>
        <td><button class="btn btn-outline" formaction="/store/cart.php" formmethod="post" name="action" value="remove" onclick="this.form.querySelector('[name=id]')?.remove();const i=document.createElement('input');i.type='hidden';i.name='id';i.value='<?= (int)$pid ?>';this.form.appendChild(i)">Remove</button></td>
      </tr>
    <?php endforeach; ?>
    </tbody></table>
    <div style="display:flex;gap:12px;margin-top:18px;flex-wrap:wrap">
      <button class="btn btn-outline">Update cart</button>
      <a href="/store/checkout.php" class="btn btn-primary" style="margin-left:auto">Proceed to checkout</a>
    </div>
  </form>
  <div class="cart-summary">
    <div class="row"><span>Subtotal</span><span><?= money($total) ?></span></div>
    <div class="row"><span>Shipping</span><span>COD</span></div>
    <div class="row total"><span>Total</span><span><?= money($total) ?></span></div>
  </div>
  <?php endif; ?>
</section>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
