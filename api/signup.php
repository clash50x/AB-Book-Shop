<?php
require_once __DIR__.'/../includes/db.php';
require_once __DIR__.'/../includes/auth.php';
require_once __DIR__.'/../includes/functions.php';
if($_SERVER['REQUEST_METHOD']!=='POST') redirect('/store/signup.php');
$name=trim($_POST['name']??''); $email=trim($_POST['email']??''); $pass=$_POST['password']??'';
if($name===''||!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($pass)<6){ flash_set('err','Invalid input'); redirect('/store/signup.php'); }
$s=$conn->prepare("SELECT id FROM users WHERE email=?"); $s->bind_param('s',$email); $s->execute();
if($s->get_result()->num_rows>0){ flash_set('err','Email already registered'); redirect('/store/signup.php'); }
$hash=password_hash($pass,PASSWORD_BCRYPT);
$role='user';
$s=$conn->prepare("INSERT INTO users(name,email,password,role) VALUES(?,?,?,?)");
$s->bind_param('ssss',$name,$email,$hash,$role); $s->execute();
$uid=$s->insert_id;
login_user(['id'=>$uid,'name'=>$name,'email'=>$email,'role'=>$role]);
flash_set('msg','Welcome to AB Book Shop!');
redirect('/store/dashboard.php');
