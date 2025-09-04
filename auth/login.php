<?php

require_once __DIR__ . '/../config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$BASE = '/Article-Web-main/';

$err = $_SESSION['flash_error'] ?? '';
$msg = $_SESSION['flash_success'] ?? '';
unset($_SESSION['flash_error'], $_SESSION['flash_success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    if ($email === '' || $pass === '') {
        $_SESSION['flash_error'] = 'Email and password are required.';
        header("Location: login.php"); exit;
    }

    $stmt = $pdo->prepare("SELECT UserID, Name, Email, Age, Phone, Role, pass FROM users WHERE Email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $_SESSION['flash_error'] = 'No account found with that email.';
        header("Location: login.php"); exit;
    }

    $stored = $user['pass'] ?? '';

    // 1) if stored looks like a password_hash() result, verify normally
    if (preg_match('/^\$2y\$|^\$2a\$|^\$argon2/', $stored)) {
        if (!password_verify($pass, $stored)) {
            $_SESSION['flash_error'] = 'Invalid credentials.';
            header("Location: login.php"); exit;
        }
        // rehash if needed
        if (password_needs_rehash($stored, PASSWORD_DEFAULT)) {
            $newHash = password_hash($pass, PASSWORD_DEFAULT);
            $pdo->prepare("UPDATE users SET pass = ? WHERE UserID = ?")->execute([$newHash, $user['UserID']]);
        }
    } else {
        // 2) Legacy/plain stored password: migrate to hash on successful match
        if (!hash_equals((string)$stored, (string)$pass)) {
            $_SESSION['flash_error'] = 'Invalid credentials.';
            header("Location: login.php"); exit;
        }
        // migrate to hashed password
        $newHash = password_hash($pass, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE users SET pass = ? WHERE UserID = ?")->execute([$newHash, $user['UserID']]);
    }

    // success: save session and redirect by role
    $_SESSION['user'] = [
        'UserID' => (int)$user['UserID'],
        'Name'   => $user['Name'],
        'Email'  => $user['Email'],
        'Age'    => $user['Age'],
        'Phone'  => $user['Phone'],
        'Role'   => $user['Role'] ?? 'user'
    ];
    $_SESSION['flash_success'] = 'Welcome back, ' . htmlspecialchars($user['Name']);

    if ($_SESSION['user']['Role'] === 'admin') {
        header("Location: {$BASE}admin/dashboard.php");
    } else {
        header("Location: {$BASE}index.php");
    }
    exit;
}

// show form
include __DIR__ . '/../partials/header.php';
?>
<div class="container py-5" style="max-width:480px;">
  <div class="card shadow-sm">
    <div class="card-body p-4">
      <h2 class="mb-3">Login</h2>
      <?php if ($err): ?><div class="alert alert-danger"><?= htmlspecialchars($err) ?></div><?php endif; ?>
      <?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

      <form method="post" action="">
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="d-grid gap-2">
          <button class="btn btn-primary">Login</button>
          <a class="btn btn-outline-secondary" href="register.php">Create account</a>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>