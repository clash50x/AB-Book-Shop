<?php
require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/auth.php';
require_once __DIR__.'/../includes/functions.php';
if($_SERVER['REQUEST_METHOD']!=='POST') redirect('/store/login.php');
$email=trim($_POST['email']??''); $pass=$_POST['password']??'';
$s=$conn->prepare("SELECT * FROM users WHERE email=?"); $s->bind_param('s',$email); $s->execute();
$u=$s->get_result()->fetch_assoc();
if(!$u||!password_verify($pass,$u['password'])){ flash_set('err','Invalid email or password'); redirect('/store/login.php'); }
login_user($u);
flash_set('msg','Welcome back, '.$u['name']);
redirect($u['role']==='admin' ? '/admin/index.php' : '/store/dashboard.php');
