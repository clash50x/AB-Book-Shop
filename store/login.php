<?php
require_once __DIR__.'/../includes/db.php';
$pageTitle='Login';
require_once __DIR__.'/../includes/header.php';
if(is_logged_in()) redirect('/store/dashboard.php');
?>
<div class="form-wrap">
  <h1>Welcome back</h1>
  <p class="sub">Sign in to your AB Book Shop account.</p>
  <form method="post" action="/api/login.php">
    <div class="field"><label>Email</label><input class="input" type="email" name="email" required></div>
    <div class="field"><label>Password</label><input class="input" type="password" name="password" required></div>
    <button class="btn btn-primary btn-block">Login</button>
  </form>
  <p class="center muted" style="margin-top:16px">No account? <a href="/store/signup.php" style="color:var(--primary);font-weight:600">Sign up</a></p>
</div>
<?php require_once __DIR__.'/../includes/footer.php'; ?>
