<?php
/**
 * admin/login.php
 * Admin login form. Validates against admins table with password_verify().
 */
session_start();

// Already logged in — go straight to dashboard
if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

require_once __DIR__ . '/../db/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please enter your username and password.';
    } else {
        $db   = getDB();
        $stmt = $db->prepare('SELECT id, password_hash FROM admins WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id']       = $admin['id'];
            $_SESSION['admin_username'] = $username;
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | MVCC</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/style.css">
  <style>
    body { display:flex; align-items:center; justify-content:center; min-height:100vh; background:var(--black); }
    .login-card {
      width:100%; max-width:420px;
      background:var(--surface-2);
      border:1px solid var(--border);
      border-radius:4px;
      padding:2.5rem;
    }
    .login-logo {
      font-family:'Playfair Display',serif;
      font-size:1.5rem;
      color:var(--white);
      margin-bottom:1.8rem;
      text-align:center;
    }
    .login-logo span { color:var(--gold); }
  </style>
</head>
<body>
  <div class="login-card">
    <div class="login-logo"><span>MVCC</span> Admin</div>

    <?php if ($error): ?>
      <div class="alert mb-3" role="alert"
           style="background:rgba(122,0,0,.2);border:1px solid var(--red-light);color:#ff6b6b;font-size:.875rem;border-radius:2px;padding:.75rem 1rem;">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="post" action="" class="form-mvcc" autocomplete="off">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username"
               required autofocus autocomplete="username"
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
      </div>
      <div class="mb-4">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password"
               required autocomplete="current-password">
      </div>
      <button type="submit" class="btn-mvcc-primary w-100">Sign In</button>
    </form>

    <p class="text-center mt-3">
      <a href="/index.php" style="font-size:.78rem; color:var(--text-muted);">← Back to site</a>
    </p>
  </div>
</body>
</html>
