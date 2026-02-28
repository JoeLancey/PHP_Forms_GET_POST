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
    <span class="topbar-title">Dashboard</span>
    <a href="logout.php" class="logout-btn">Logout</a>
  </div>

  <div class="content">
    <div class="welcome">
      <h2>Hello, <?= $full_name ?>!</h2>
      <p>Welcome back. You are logged in as <strong><?= $username ?></strong>.</p>
    </div>
  </div>

</body>
</html>