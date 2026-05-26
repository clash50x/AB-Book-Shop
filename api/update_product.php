<?php
require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/auth.php';
require_once __DIR__.'/../includes/functions.php';
require_admin();
if($_SERVER['REQUEST_METHOD']!=='POST') redirect('/admin/products.php');
$id=(int)$_POST['id']; $title=trim($_POST['title']??''); $desc=trim($_POST['description']??'');
$price=(float)($_POST['price']??0); $image=trim($_POST['image']??''); $cat=(int)($_POST['category_id']??0);
$s=$conn->prepare("UPDATE products SET title=?, description=?, price=?, image=?, category_id=? WHERE id=?");
$s->bind_param('ssdsii',$title,$desc,$price,$image,$cat,$id); $s->execute();
flash_set('msg','Product updated'); redirect('/admin/products.php');
