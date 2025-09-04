<?php
require_once __DIR__ . '/../config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$uid = (int)($_GET['id'] ?? ($_SESSION['user']['UserID'] ?? 0));
if (!$uid) {
    header('Location: ../index.php');
    exit;
}

$stmt = $pdo->prepare("
  SELECT u.UserID, u.Name, u.Email, u.Age, u.Phone, u.Role, p.Bio, p.ProfileID
  FROM users u
  LEFT JOIN profiles p ON u.UserID = p.UserID
  WHERE u.UserID = ?
  LIMIT 1
");
$stmt->execute([$uid]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$profile) {
    echo "<div class='container py-5'><div class='alert alert-danger'>User not found.</div></div>";
    exit;
}

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>
<div class="container py-5">
  <h2><?= htmlspecialchars($profile['Name']) ?>'s Profile</h2>

  <ul class="list-group mb-3">
    <li class="list-group-item"><strong>Name:</strong> <?= htmlspecialchars($profile['Name']) ?></li>
    <li class="list-group-item"><strong>Email:</strong> <?= htmlspecialchars($profile['Email']) ?></li>
    <li class="list-group-item"><strong>Age:</strong> <?= htmlspecialchars($profile['Age']) ?></li>
    <li class="list-group-item"><strong>Phone:</strong> <?= htmlspecialchars($profile['Phone']) ?></li>
    <li class="list-group-item"><strong>Role:</strong> <?= htmlspecialchars($profile['Role']) ?></li>
    <li class="list-group-item"><strong>Bio:</strong><br><?= nl2br(htmlspecialchars($profile['Bio'] ?? '')) ?></li>
  </ul>

  <?php
  $canEdit = false;
  if (isset($_SESSION['user'])) {
      $me = $_SESSION['user'];
      if (strtolower($me['Role'] ?? '') === 'admin' || $me['UserID'] == $profile['UserID']) $canEdit = true;
  }
  ?>
  <?php if ($canEdit): ?>
    <a class="btn btn-warning" href="/Article-Web-main/profiles/edit.php?id=<?= $profile['UserID'] ?>">Edit Profile</a>
  <?php endif; ?>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>