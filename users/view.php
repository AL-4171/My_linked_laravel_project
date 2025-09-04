<?php
require_once __DIR__ . '/../config.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM users WHERE UserID=?");
$stmt->execute([$id]);
$user = $stmt->fetch();
if (!$user) die("User not found");

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>
<div class="container py-5">
  <h2>User Profile</h2>
  <ul class="list-group">
    <li class="list-group-item"><b>Name:</b> <?= htmlspecialchars($user['Name']) ?></li>
    <li class="list-group-item"><b>Email:</b> <?= htmlspecialchars($user['Email']) ?></li>
    <li class="list-group-item"><b>Age:</b> <?= htmlspecialchars($user['Age']) ?></li>
    <li class="list-group-item"><b>Phone:</b> <?= htmlspecialchars($user['Phone']) ?></li>
    <li class="list-group-item"><b>Role:</b> <?= htmlspecialchars($user['Role']) ?></li>
  </ul>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>