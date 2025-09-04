<?php
require_once __DIR__ . '/../config.php';

$tags = $pdo->query("SELECT * FROM tags ORDER BY TagID DESC")->fetchAll();

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>
<div class="container py-4">
  <h2>Tags</h2>
  <a href="create.php" class="btn btn-success mb-3">+ Create Tag</a>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Name</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($tags as $t): ?>
        <tr>
          <td><?= htmlspecialchars($t['Name']) ?></td>
          <td>
            <a href="view.php?id=<?= $t['TagID'] ?>" class="btn btn-sm btn-primary">View</a>
            <a href="edit.php?id=<?= $t['TagID'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="delete.php?id=<?= $t['TagID'] ?>" class="btn btn-sm btn-danger"
               onclick="return confirm('Delete this tag?')">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>