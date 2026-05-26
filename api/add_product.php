<?php
require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/auth.php';
require_once __DIR__.'/../includes/functions.php';
require_admin();
if($_SERVER['REQUEST_METHOD']!=='POST') redirect('/admin/products.php');
$title=trim($_POST['title']??''); $desc=trim($_POST['description']??'');
$price=(float)($_POST['price']??0); $image=trim($_POST['image']??''); $cat=(int)($_POST['category_id']??0);
if($title===''||$price<=0){ flash_set('err','Title and price required'); redirect('/admin/products.php'); }
$s=$conn->prepare("INSERT INTO products(title,description,price,image,category_id) VALUES(?,?,?,?,?)");
$s->bind_param('ssdsi',$title,$desc,$price,$image,$cat); $s->execute();
flash_set('msg','Product added'); redirect('/admin/products.php');
