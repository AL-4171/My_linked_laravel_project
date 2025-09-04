<?php
require_once __DIR__ . '/../config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
    }


$user = $_SESSION['user'];


$stmt = $pdo->prepare("
    SELECT u.UserID, u.Name, u.Email, u.Role, p.Bio
    FROM users u
    LEFT JOIN profiles p ON u.UserID = p.UserID
    WHERE u.UserID = ?
");
$stmt->execute([$user['UserID']]);
$current = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $bio      = trim($_POST['bio'] ?? '');

    
    if ($password !== '') {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $pdo->prepare("UPDATE users SET Name=?, Email=?, Password=? WHERE UserID=?")
            ->execute([$name, $email, $hashed, $user['UserID']]);
    } else {
        $pdo->prepare("UPDATE users SET Name=?, Email=? WHERE UserID=?")
            ->execute([$name, $email, $user['UserID']]);
    }

   
    $exists = $pdo->prepare("SELECT ProfileID FROM profiles WHERE UserID=?");
    $exists->execute([$user['UserID']]);
    if ($exists->fetchColumn()) {
        $pdo->prepare("UPDATE profiles SET Bio=? WHERE UserID=?")
            ->execute([$bio, $user['UserID']]);
    } else {
        $pdo->prepare("INSERT INTO profiles (UserID, Bio) VALUES (?, ?)")
            ->execute([$user['UserID'], $bio]);
    }

    $_SESSION['user']['Name'] = $name;
    $_SESSION['user']['Email'] = $email;

    header("Location: view.php?id=" . $user['UserID']);
    exit;
}

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>

<div class="container py-5">
  <h2>Edit Profile</h2>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text" name="name" class="form-control"
             value="<?= htmlspecialchars($current['Name']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control"
             value="<?= htmlspecialchars($current['Email']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Password (leave blank to keep unchanged)</label>
      <input type="password" name="password" class="form-control">
    </div>
    <div class="mb-3">
      <label class="form-label">Bio</label>
      <textarea name="bio" class="form-control" rows="4"><?= htmlspecialchars($current['Bio'] ?? '') ?></textarea>
    </div>
    <button type="submit" class="btn btn-success">Save Changes</button>
    <a href="view.php?id=<?= $user['UserID'] ?>" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
