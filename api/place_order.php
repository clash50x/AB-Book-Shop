<?php
require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/auth.php';
require_once __DIR__.'/../includes/functions.php';
require_login('/store/login.php');
if($_SERVER['REQUEST_METHOD']!=='POST') redirect('/store/checkout.php');
$uid=current_user()['id'];
$items=cart();
if(empty($items)){ flash_set('err','Cart is empty'); redirect('/store/cart.php'); }

$addr_id=(int)($_POST['address_id']??0);
if(!$addr_id){
  $fa=trim($_POST['full_address']??''); $ci=trim($_POST['city']??''); $ph=trim($_POST['phone']??'');
  if($fa===''||$ci===''||$ph===''){ flash_set('err','Provide a delivery address'); redirect('/store/checkout.php'); }
  $s=$conn->prepare("INSERT INTO addresses(user_id,full_address,city,phone) VALUES(?,?,?,?)");
  $s->bind_param('isss',$uid,$fa,$ci,$ph); $s->execute();
  $addr_id=$s->insert_id;
} else {
  $s=$conn->prepare("SELECT id FROM addresses WHERE id=? AND user_id=?");
  $s->bind_param('ii',$addr_id,$uid); $s->execute();
  if($s->get_result()->num_rows===0){ flash_set('err','Invalid address'); redirect('/store/checkout.php'); }
}

$ids=implode(',',array_map('intval',array_keys($items)));
$rs=$conn->query("SELECT id,price FROM products WHERE id IN ($ids)");
$total=0; $prices=[];
while($r=$rs->fetch_assoc()){ $prices[$r['id']]=$r['price']; $total += $r['price']*$items[$r['id']]; }

$conn->begin_transaction();
try{
  $s=$conn->prepare("INSERT INTO orders(user_id,address_id,total_price,status) VALUES(?,?,?, 'Pending')");
  $s->bind_param('iid',$uid,$addr_id,$total); $s->execute();
  $oid=$s->insert_id;
  $oi=$conn->prepare("INSERT INTO order_items(order_id,product_id,quantity,price) VALUES(?,?,?,?)");
  foreach($items as $pid=>$qty){
    $p=$prices[$pid]??0; $q=(int)$qty;
    $oi->bind_param('iiid',$oid,$pid,$q,$p); $oi->execute();
  }
  $conn->commit();
  cart_clear();
  flash_set('msg','Order placed successfully! Order #'.$oid);
  redirect('/store/dashboard.php?tab=order&id='.$oid);
}catch(Throwable $e){
  $conn->rollback();
  flash_set('err','Failed to place order');
  redirect('/store/checkout.php');
}
