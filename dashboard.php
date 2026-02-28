<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user'])) { header('Location: index.php'); exit; }

$username   = htmlspecialchars($_SESSION['user']);
$full_name  = htmlspecialchars($_SESSION['full_name'] ?? '');
$email      = htmlspecialchars($_SESSION['email'] ?? '');
$login_time = htmlspecialchars($_SESSION['login_time'] ?? '');
$last_login = isset($_COOKIE['last_login']) ? htmlspecialchars($_COOKIE['last_login']) : 'First visit';
$remembered = isset($_COOKIE['remembered_username']) ? htmlspecialchars($_COOKIE['remembered_username']) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="dash">

  <div class="topbar">
    <span>Dashboard</span>
    <div class="topbar-right">
      <span class="topbar-user">Hello, <?= $full_name ?>!</span>
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <div class="content">

    <div class="welcome">
      <h2>Welcome, <?= $full_name ?>!</h2>
      <p>Logged in as <strong><?= $username ?></strong>. Session started at <?= $login_time ?>.</p>
    </div>

    <div class="card-row">

      <div class="card">
        <h3>Session Data</h3>
        <div class="row"><span>Username</span><span><?= $username ?></span></div>
        <div class="row"><span>Full Name</span><span><?= $full_name ?></span></div>
        <div class="row"><span>Email</span><span><?= $email ?></span></div>
        <div class="row"><span>Login Time</span><span><?= $login_time ?></span></div>
        <div class="row"><span>Status</span><span><span class="badge-on">Active</span></span></div>
      </div>

      <div class="card">
        <h3>Cookie Data</h3>
        <div class="row">
          <span>Remember Me</span>
          <span><?= $remembered ? "<span class='badge-on'>{$remembered}</span>" : "<span class='badge-off'>Not set</span>" ?></span>
        </div>
        <div class="row"><span>Last Login</span><span><?= $last_login ?></span></div>
        <div class="row">
          <span>Cookie Status</span>
          <span><?= $remembered ? "<span class='badge-on'>Set (30 days)</span>" : "<span class='badge-off'>Not set</span>" ?></span>
        </div>
      </div>

    </div>

    <div class="card code-card">
      <h3>$_SESSION &amp; $_COOKIE Values</h3>
      <?php foreach ($_SESSION as $k => $v): ?>
        <div class="code-line"><b class="session-key">$_SESSION['<?= htmlspecialchars($k) ?>']</b> = "<?= htmlspecialchars((string)$v) ?>"</div>
      <?php endforeach; ?>
      <hr class="code-divider">
      <?php if (!empty($_COOKIE)): ?>
        <?php foreach ($_COOKIE as $k => $v): ?>
          <div class="code-line"><b class="cookie-key">$_COOKIE['<?= htmlspecialchars($k) ?>']</b> = "<?= htmlspecialchars((string)$v) ?>"</div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="code-empty">No cookies set.</div>
      <?php endif; ?>
    </div>

  </div>

</body>
</html>