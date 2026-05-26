<?php
require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/auth.php';
require_once __DIR__.'/../includes/functions.php';
require_admin();
if($_SERVER['REQUEST_METHOD']!=='POST') redirect('/admin/categories.php');
$action=$_POST['action']??'add';
if($action==='add'){
  $n=trim($_POST['name']??''); $l=trim($_POST['logo']??'');
  if($n===''){ flash_set('err','Name required'); redirect('/admin/categories.php'); }
  $s=$conn->prepare("INSERT INTO categories(name,logo) VALUES(?,?)"); $s->bind_param('ss',$n,$l); $s->execute();
  flash_set('msg','Category added');
} elseif($action==='update'){
  $id=(int)$_POST['id']; $n=trim($_POST['name']??''); $l=trim($_POST['logo']??'');
  $s=$conn->prepare("UPDATE categories SET name=?, logo=? WHERE id=?"); $s->bind_param('ssi',$n,$l,$id); $s->execute();
  flash_set('msg','Category updated');
} elseif($action==='delete'){
  $id=(int)$_POST['id'];
  $s=$conn->prepare("DELETE FROM categories WHERE id=?"); $s->bind_param('i',$id); $s->execute();
  flash_set('msg','Category deleted');
}
redirect('/admin/categories.php');
