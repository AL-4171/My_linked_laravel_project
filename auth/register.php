<?php

require_once __DIR__ . '/../config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

// convenience
$BASE = '/Article-Web-main/';

$err = $_SESSION['flash_error'] ?? '';
$msg = $_SESSION['flash_success'] ?? '';
unset($_SESSION['flash_error'], $_SESSION['flash_success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name   = trim($_POST['name'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $pass   = $_POST['password'] ?? '';
    $pass2  = $_POST['password2'] ?? '';
    $age    = $_POST['age'] !== '' ? (int)$_POST['age'] : null;
    $phone  = trim($_POST['phone'] ?? '');
    $role   = ($_POST['role'] ?? 'user') === 'admin' ? 'admin' : 'user';

    // basic validation
    if ($name === '' || $email === '' || $pass === '') {
        $_SESSION['flash_error'] = 'Name, email and password are required.';
        header("Location: register.php"); exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['flash_error'] = 'Enter a valid email address.';
        header("Location: register.php"); exit;
    }
    if (strlen($pass) < 6) {
        $_SESSION['flash_error'] = 'Password must be at least 6 characters.';
        header("Location: register.php"); exit;
    }
    if ($pass !== $pass2) {
        $_SESSION['flash_error'] = 'Passwords do not match.';
        header("Location: register.php"); exit;
    }

    // check duplicate email
    $check = $pdo->prepare("SELECT UserID FROM users WHERE Email = ? LIMIT 1");
    $check->execute([$email]);
    if ($check->fetch()) {
        $_SESSION['flash_error'] = 'Email already exists.';
        header("Location: register.php"); exit;
    }

    // create user (hashed pass)
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (Name, Email, pass, Age, Phone, Role) VALUES (?, ?, ?, ?, ?, ?)");
    try {
        $stmt->execute([$name, $email, $hash, $age, $phone, $role]);
        $uid = $pdo->lastInsertId();

        // create a profile row for the user (empty bio by default)
        $pstmt = $pdo->prepare("INSERT INTO profiles (Bio, UserID) VALUES (?, ?)");
        $pstmt->execute(['', $uid]);

        // store session
        $_SESSION['user'] = [
            'UserID' => (int)$uid,
            'Name'   => $name,
            'Email'  => $email,
            'Age'    => $age,
            'Phone'  => $phone,
            'Role'   => $role
        ];
        $_SESSION['flash_success'] = 'Welcome, ' . htmlspecialchars($name);

        // redirect by role
        if ($role === 'admin') {
            header("Location: /Article-Web-main/"); // create admin dashboard file
        } else {
            header("Location: /Article-Web-main/"); // user dashboard
        }
        exit;
    } catch (PDOException $e) {
        // generic fail
        $_SESSION['flash_error'] = 'Registration failed.';
        header("Location: register.php"); exit;
    }
}

// show form (same file)
include __DIR__ . '/../partials/header.php';
?>
<div class="container py-5" style="max-width:720px;">
  <div class="card shadow-sm">
    <div class="card-body p-4">
      <h2 class="mb-4">Create an account</h2>
      <?php if ($err): ?><div class="alert alert-danger"><?= htmlspecialchars($err) ?></div><?php endif; ?>
      <?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

      <form method="post" action="">
        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password2" class="form-control" required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4 mb-3">
            <label class="form-label">Age</label>
            <input type="number" name="age" class="form-control" value="<?= htmlspecialchars($_POST['age'] ?? '') ?>">
          </div>
          <div class="col-md-8 mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Role</label>
          <select name="role" class="form-select">
            <option value="user" <?= (($_POST['role'] ?? '') === 'user') ? 'selected' : '' ?>>User</option>
            <option value="admin" <?= (($_POST['role'] ?? '') === 'admin') ? 'selected' : '' ?>>Admin</option>
          </select>
        </div>

        <div class="d-grid gap-2">
          <button class="btn btn-primary">Sign up</button>
          <a class="btn btn-outline-secondary" href="login.php">I already have an account</a>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>