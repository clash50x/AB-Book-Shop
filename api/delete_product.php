<?php
require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/auth.php';
require_once __DIR__.'/../includes/functions.php';
require_admin();
$id=(int)($_POST['id']??$_GET['id']??0);
$s=$conn->prepare("DELETE FROM products WHERE id=?"); $s->bind_param('i',$id); $s->execute();
flash_set('msg','Product deleted'); redirect('/admin/products.php');
