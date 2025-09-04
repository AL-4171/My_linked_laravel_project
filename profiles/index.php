<?php
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['user']) || strtolower($_SESSION['user']['Role'] ?? '') !== 'admin') die("Access denied");

$sql = "SELECT p.ProfileID, p.Bio, u.UserID, u.Name, u.Email FROM profiles p JOIN users u ON p.UserID = u.UserID ORDER BY p.ProfileID DESC";
$profiles = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>
<div class="container py-5">
  <h2>Profiles</h2>
  <a href="create.php" class="btn btn-success mb-3">+ Create Profile</a>
  <table class="table table-bordered">
    <thead><tr><th>User</th><th>Email</th><th>Bio</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($profiles as $p): ?>
        <tr>
          <td><?= htmlspecialchars($p['Name']) ?></td>
          <td><?= htmlspecialchars($p['Email']) ?></td>
          <td><?= htmlspecialchars($p['Bio']) ?></td>
          <td>
            <a href="view.php?id=<?= $p['UserID'] ?>" class="btn btn-sm btn-primary">View</a>
            <a href="edit.php?id=<?= $p['UserID'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="delete.php?id=<?= $p['ProfileID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete profile?')">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>