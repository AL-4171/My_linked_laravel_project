<?php
require_once __DIR__ . '/../config.php';
include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['Role']!=='admin') die("Access denied");

$users = $pdo->query("SELECT UserID, Name, Email, Age, Phone, Role FROM users ORDER BY UserID")->fetchAll();
?>
<div class="container py-5">
  <h2>Users</h2>
  <a href="create.php" class="btn btn-success mb-3">Create User</a>
  <table class="table table-bordered">
    <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Age</th><th>Phone</th><th>Role</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach($users as $u): ?>
      <tr>
        <td><?= $u['UserID'] ?></td>
        <td><?= htmlspecialchars($u['Name']) ?></td>
        <td><?= htmlspecialchars($u['Email']) ?></td>
        <td><?= $u['Age'] ?></td>
        <td><?= htmlspecialchars($u['Phone']) ?></td>
        <td><?= htmlspecialchars($u['Role']) ?></td>
        <td>
          <a href="edit.php?id=<?= $u['UserID'] ?>" class="btn btn-sm btn-warning">Edit</a>
          <a href="delete.php?id=<?= $u['UserID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete user?')">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>