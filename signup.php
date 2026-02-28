<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['user'])) { header('Location: dashboard.php'); exit; }

$errors  = [];
$success = '';
$old     = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['full_name'] = trim($_POST['full_name'] ?? '');
    $old['username']  = trim($_POST['username']  ?? '');
    $old['email']     = trim($_POST['email']     ?? '');
    $password         = $_POST['password']         ?? '';
    $confirm          = $_POST['confirm_password'] ?? '';

    
    if (!$old['full_name']) $errors['full_name'] = 'Full name is required.';
    if (!$old['username'])  $errors['username']  = 'Username is required.';
    if (!$old['email'])     $errors['email']     = 'Email is required.';
    if (!$password)         $errors['password']  = 'Password is required.';

    
    if (!isset($errors['username'])) {
        if (strlen($old['username']) < 3)       $errors['username'] = 'Username must be at least 3 characters.';
        elseif (strlen($old['username']) > 20)  $errors['username'] = 'Username must be at most 20 characters.';
    }
    if (!isset($errors['password']) && strlen($password) < 8)
        $errors['password'] = 'Password must be at least 8 characters.';

   
    if (!isset($errors['email']) && !filter_var($old['email'], FILTER_VALIDATE_EMAIL))
        $errors['email'] = 'Please enter a valid email address.';

    
    if (!isset($errors['password']) && $password !== $confirm)
        $errors['confirm'] = 'Passwords do not match.';

    
    if (empty($errors)) {
        $users = getUsers();
        foreach ($users as $u) {
            if ($u['email']    === $old['email'])    { $errors['email']    = 'Email already registered.'; break; }
            if ($u['username'] === $old['username']) { $errors['username'] = 'Username already taken.';   break; }
        }
    }

    
    if (empty($errors)) {
        $users   = getUsers();
        $users[] = [
            'id'         => uniqid(),
            'full_name'  => $old['full_name'],
            'username'   => $old['username'],
            'email'      => $old['email'],
            'password'   => password_hash($password, PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
        ];
        saveUsers($users);
        $success = 'Account created! You can now <a href="index.php">log in</a>.';
        $old = [];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign Up</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="box">
  <h2>Sign Up</h2>
  <p class="subtitle">Create a new account</p>

  <?php if ($success): ?>
    <div class="msg-success"><?= $success ?></div>
  <?php endif; ?>

  <form method="POST" action="signup.php">
    <div class="form-group">
      <label>Full Name</label>
      <input type="text" name="full_name"
             value="<?= htmlspecialchars($old['full_name'] ?? '') ?>"
             placeholder="Enter your Full Name"
             class="<?= isset($errors['full_name']) ? 'err' : '' ?>">
      <?php if (isset($errors['full_name'])): ?>
        <div class="field-err"><?= $errors['full_name'] ?></div>
      <?php endif; ?>
    </div>

    <div class="form-group">
      <label>Username</label>
      <input type="text" name="username"
             value="<?= htmlspecialchars($old['username'] ?? '') ?>"
             placeholder="Enter your Username"
             class="<?= isset($errors['username']) ? 'err' : '' ?>">
      <?php if (isset($errors['username'])): ?>
        <div class="field-err"><?= $errors['username'] ?></div>
      <?php endif; ?>
    </div>

    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email"
             value="<?= htmlspecialchars($old['email'] ?? '') ?>"
             placeholder="Enter your Email"
             class="<?= isset($errors['email']) ? 'err' : '' ?>">
      <?php if (isset($errors['email'])): ?>
        <div class="field-err"><?= $errors['email'] ?></div>
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

    <div class="form-group">
      <label>Confirm Password</label>
      <input type="password" name="confirm_password"
             placeholder="Confirm your Password"
             class="<?= isset($errors['confirm']) ? 'err' : '' ?>">
      <?php if (isset($errors['confirm'])): ?>
        <div class="field-err"><?= $errors['confirm'] ?></div>
      <?php endif; ?>
    </div>

    <button type="submit">Create Account</button>
  </form>

  <div class="link-row">Already have an account? <a href="index.php">Login</a></div>
</div>
</body>
</html>