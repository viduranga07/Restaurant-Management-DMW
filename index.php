<?php
require_once 'config.php';
if (isset($_SESSION['user'])) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = "Enter a valid email and password.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $u = $stmt->fetch();
        if ($u && password_verify($password, $u['password'])) {
            $_SESSION['user'] = ['id'=>$u['id'],'name'=>$u['name'],'role'=>$u['role']];
            header('Location: dashboard.php'); exit;
        }
        $error = "Invalid login details.";
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>GastroNova Login</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body class="login-page">
<div class="login-card">
  <div class="brand large">GASTRONOVA <span>RMS</span></div>
  <p class="muted">Restaurant Management System</p>
  <?php if($error): ?><div class="alert danger"><?= e($error) ?></div><?php endif; ?>
  <form method="post" id="loginForm">
    <label>Email</label><input type="email" name="email" required placeholder="manager@gastronova.com">
    <label>Password</label><input type="password" name="password" required placeholder="••••••••">
    <button class="btn primary full">Sign In</button>
  </form>
  <p class="hint">Demo: manager@gastronova.com / password</p>
</div>
</body></html>
