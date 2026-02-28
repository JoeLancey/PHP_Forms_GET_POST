<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['user'])) { header('Location: dashboard.php'); exit; }

$errors = [];
$old    = [];

// GET message (from logout)
$get_msg = ($_GET['msg'] ?? '') === 'logged_out' ? 'You have been logged out.' : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['username'] = trim($_POST['username'] ?? '');
    $password        = $_POST['password'] ?? '';
    $remember        = isset($_POST['remember']);

    // Validation 1: Required
    if (!$old['username']) $errors['username'] = 'Username or email is required.';
    if (!$password)        $errors['password']  = 'Password is required.';

    // Validation 2: Min length
    if (!isset($errors['password']) && strlen($password) < 8)
        $errors['password'] = 'Password must be at least 8 characters.';

    if (empty($errors)) {
        $user = findUser($old['username']);
        if (!$user || !password_verify($password, $user['password'])) {
            $errors['general'] = 'Incorrect username/email or password.';
        } else {
            // Start session
            $_SESSION['user']       = $user['username'];
            $_SESSION['full_name']  = $user['full_name'];
            $_SESSION['email']      = $user['email'];
            $_SESSION['login_time'] = date('Y-m-d H:i:s');

            // Cookie: remember username
            if ($remember) {
                setcookie('remembered_username', $user['username'], time() + (30 * 86400), '/');
            } else {
                setcookie('remembered_username', '', time() - 3600, '/');
            }

            // Cookie: last login time
            setcookie('last_login', date('Y-m-d H:i:s'), time() + (365 * 86400), '/');

            header('Location: dashboard.php');
            exit;
        }
    }
}

$saved = $_COOKIE['remembered_username'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="box">
  <h2>Login</h2>
  <p class="subtitle">Sign in to your account</p>

  <?php if ($get_msg): ?>
    <div class="msg-success"><?= htmlspecialchars($get_msg) ?></div>
  <?php endif; ?>

  <?php if (isset($errors['general'])): ?>
    <div class="msg-error"><?= htmlspecialchars($errors['general']) ?></div>
  <?php endif; ?>

  <form method="POST" action="index.php">
    <div class="form-group">
      <label>Username or Email</label>
      <input type="text" name="username"
             value="<?= htmlspecialchars($old['username'] ?? $saved) ?>"
             placeholder="Enter username or email"
             class="<?= isset($errors['username']) ? 'err' : '' ?>">
      <?php if (isset($errors['username'])): ?>
        <div class="field-err"><?= $errors['username'] ?></div>
      <?php endif; ?>
    </div>

    <div class="form-group">
      <label>Password</label>
      <input type="password" name="password"
             placeholder="Min. 8 characters"
             class="<?= isset($errors['password']) ? 'err' : '' ?>">
      <?php if (isset($errors['password'])): ?>
        <div class="field-err"><?= $errors['password'] ?></div>
      <?php endif; ?>
    </div>

    <div class="check-row">
      <input type="checkbox" name="remember" id="remember"
             <?= $saved ? 'checked' : '' ?>>
      <label for="remember" class="check-label">Remember me for 30 days</label>
    </div>

    <button type="submit">Login</button>
  </form>

  <div class="link-row">Don't have an account? <a href="signup.php">Sign up</a></div>
</div>
</body>
</html>