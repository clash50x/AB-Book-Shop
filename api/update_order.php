<?php
require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/auth.php';
require_once __DIR__.'/../includes/functions.php';
require_admin();
if($_SERVER['REQUEST_METHOD']!=='POST') redirect('/admin/orders.php');
$id=(int)$_POST['id']; $status=$_POST['status']??'Pending';
$allowed=['Pending','Confirmed','Shipped','Delivered'];
if(!in_array($status,$allowed,true)){ flash_set('err','Invalid status'); redirect('/admin/orders.php'); }
$s=$conn->prepare("UPDATE orders SET status=? WHERE id=?"); $s->bind_param('si',$status,$id); $s->execute();
flash_set('msg','Order updated'); redirect('/admin/orders.php');
