<?php
if (session_status() === PHP_SESSION_NONE) session_start();
function current_user(){ return $_SESSION['user'] ?? null; }
function is_logged_in(){ return !empty($_SESSION['user']); }
function is_admin(){ return is_logged_in() && ($_SESSION['user']['role'] ?? '') === 'admin'; }
function require_login($redirect='/store/login.php'){ if(!is_logged_in()){ flash_set('msg','Please login first'); header("Location: $redirect"); exit; } }
function require_admin(){ if(!is_admin()){ header('Location: /admin/login.php'); exit; } }
function login_user($row){
    $_SESSION['user'] = [
        'id'=>(int)$row['id'],'name'=>$row['name'],'email'=>$row['email'],'role'=>$row['role']
    ];
}
function logout_user(){ $_SESSION=[]; if(ini_get('session.use_cookies')){ $p=session_get_cookie_params(); setcookie(session_name(),'',time()-42000,$p['path'],$p['domain'],$p['secure'],$p['httponly']);} session_destroy(); }
