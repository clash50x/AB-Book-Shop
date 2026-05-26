<?php
require_once __DIR__.'/../includes/db.php';
$pageTitle='Sign up';
require_once __DIR__.'/../includes/header.php';
if(is_logged_in()) redirect('/store/dashboard.php');
?>
<div class="form-wrap">
  <h1>Create your account</h1>
  <p class="sub">Join AB Book Shop in seconds.</p>
  <form method="post" action="/api/signup.php">
    <div class="field"><label>Full name</label><input class="input" name="name" required></div>
    <div class="field"><label>Email</label><input class="input" type="email" name="email" required></div>
    <div class="field"><label>Password</label><input class="input" type="password" name="password" minlength="6" required></div>
    <button class="btn btn-primary btn-block">Sign up</button>
  </form>
  <p class="center muted" style="margin-top:16px">Already a member? <a href="/store/login.php" style="color:var(--primary);font-weight:600">Login</a></p>
</div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
