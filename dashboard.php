<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user'])) { header('Location: index.php'); exit; }

$username   = htmlspecialchars($_SESSION['user']);
$full_name  = htmlspecialchars($_SESSION['full_name'] ?? '');
$email      = htmlspecialchars($_SESSION['email'] ?? '');
$login_time = htmlspecialchars($_SESSION['login_time'] ?? '');
$session_id = session_id();
$last_login = isset($_COOKIE['last_login']) ? htmlspecialchars($_COOKIE['last_login']) : 'First visit';
$remembered = isset($_COOKIE['remembered_username']) ? htmlspecialchars($_COOKIE['remembered_username']) : 'Not set';
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
    <span>MyApp</span>
    <a href="logout.php" class="logout-btn">Logout</a>
  </div>

  <div class="content">
    <div class="welcome">
      <h2>Hello, <?= $full_name ?>!</h2>
      <p>Welcome back. You are logged in as <strong><?= $username ?></strong>.</p>
    </div>

    <div class="card-row">

      <!-- SESSION DATA -->
      <div class="card">
        <h3>🔐 Session Data</h3>
        <div class="row">
          <span>Session ID</span>
          <span><?= substr($session_id, 0, 12) ?>…</span>
        </div>
        <div class="row">
          <span>Username</span>
          <span><?= $username ?></span>
        </div>
        <div class="row">
          <span>Full Name</span>
          <span><?= $full_name ?></span>
        </div>
        <div class="row">
          <span>Email</span>
          <span><?= $email ?></span>
        </div>
        <div class="row">
          <span>Login Time</span>
          <span><?= $login_time ?></span>
        </div>
        <div class="row">
          <span>Status</span>
          <span><span class="badge-on">Active</span></span>
        </div>
      </div>

      <!-- COOKIE DATA -->
      <div class="card">
        <h3>🍪 Cookie Data</h3>
        <div class="row">
          <span>Last Login</span>
          <span><?= $last_login ?></span>
        </div>
        <div class="row">
          <span>Remembered User</span>
          <span><?= $remembered ?></span>
        </div>
        <div class="row">
          <span>Remember Me</span>
          <span>
            <?= isset($_COOKIE['remembered_username'])
              ? '<span class="badge-on">Yes (30 days)</span>'
              : '<span class="badge-off">No</span>' ?>
          </span>
        </div>
        <div class="row">
          <span>Cookie Expires</span>
          <span>
            <?= isset($_COOKIE['remembered_username'])
              ? date('Y-m-d', time() + (30 * 86400))
              : '—' ?>
          </span>
        </div>
        <div class="row">
          <span>last_login cookie</span>
          <span><span class="badge-on">Set (1 year)</span></span>
        </div>
      </div>

    </div>
  </div>

</body>
</html>
