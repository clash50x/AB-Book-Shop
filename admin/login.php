<?php
require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/auth.php';
require_once __DIR__.'/../includes/functions.php';
if(is_admin()) redirect('/admin/index.php');
if($_SERVER['REQUEST_METHOD']==='POST'){
  $email=trim($_POST['email']??''); $pass=$_POST['password']??'';
  $s=$conn->prepare("SELECT * FROM users WHERE email=? AND role='admin'");
  $s->bind_param('s',$email); $s->execute();
  $u=$s->get_result()->fetch_assoc();
  if(!$u||!password_verify($pass,$u['password'])){ flash_set('err','Invalid admin credentials'); redirect('/admin/login.php'); }
  login_user($u); redirect('/admin/index.php');
}
?><!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin Login</title><link rel="stylesheet" href="/assets/css/style.css"></head><body style="background:#142D33">
<div class="form-wrap" style="margin-top:90px">
  <h1>Admin login</h1><p class="sub">Restricted area — staff only.</p>
  <?php if($m=flash_get('err')): ?><div class="toast toast-err"><?= e($m) ?></div><?php endif; ?>
  <form method="post">
    <div class="field"><label>Email</label><input class="input" type="email" name="email" required></div>
    <div class="field"><label>Password</label><input class="input" type="password" name="password" required></div>
    <button class="btn btn-primary btn-block">Login</button>
  </form>
</div></body></html>
