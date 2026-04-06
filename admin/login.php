<?php
require_once __DIR__ . '/../includes/site.php';
mvcc_start_session();
$baseUrl = mvcc_base_url();

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
        $db = getDB();
        $stmt = $db->prepare('SELECT id, password_hash FROM admins WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $username;
            mvcc_csrf_token();
            header('Location: dashboard.php');
            exit;
        }

        $error = 'Invalid username or password.';
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
  <link rel="stylesheet" href="<?= htmlspecialchars($baseUrl, ENT_QUOTES) ?>assets/css/style.css">
</head>
<body class="admin-shell admin-auth">
  <div class="admin-auth-card">
    <div class="admin-auth-mark">M</div>
    <div class="admin-auth-title"><span>MVCC</span> Admin</div>
    <p class="admin-auth-subtitle">Secure access for managing the club site and live content.</p>

    <?php if ($error): ?>
      <div class="admin-alert admin-alert--error" role="alert">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="post" action="" class="form-mvcc" autocomplete="off">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input
          type="text"
          class="form-control"
          id="username"
          name="username"
          required
          autofocus
          autocomplete="username"
          value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
        >
      </div>
      <div class="mb-4">
        <label for="password" class="form-label">Password</label>
        <input
          type="password"
          class="form-control"
          id="password"
          name="password"
          required
          autocomplete="current-password"
        >
      </div>
      <button type="submit" class="btn-mvcc-primary w-100">Sign In</button>
    </form>

    <p class="admin-auth-footer">
      <a href="<?= htmlspecialchars($baseUrl, ENT_QUOTES) ?>index.php">Back to site</a>
    </p>
  </div>
</body>
</html>
