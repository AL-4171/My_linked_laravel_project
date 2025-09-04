<?php
require_once __DIR__ . '/../config.php';

$sql = "
  SELECT c.CommentID, c.Content, c.CreatedAt,
         u.Name AS UserName, p.Title AS PostTitle
  FROM comments c
  JOIN users u ON c.UserID = u.UserID
  JOIN posts p ON c.PostID = p.PostID
  ORDER BY c.CreatedAt DESC
";
$comments = $pdo->query($sql)->fetchAll();

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>
<div class="container py-4">
  <h2>Comments</h2>
  <a href="create.php" class="btn btn-success mb-3">+ Add Comment</a>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>User</th>
        <th>Post</th>
        <th>Content</th>
        <th>Created At</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($comments as $c): ?>
        <tr>
          <td><?= htmlspecialchars($c['UserName']) ?></td>
          <td><?= htmlspecialchars($c['PostTitle']) ?></td>
          <td><?= htmlspecialchars($c['Content']) ?></td>
          <td><?= $c['CreatedAt'] ?></td>
          <td>
            <a href="view.php?id=<?= $c['CommentID'] ?>" class="btn btn-sm btn-primary">View</a>
            <a href="edit.php?id=<?= $c['CommentID'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="delete.php?id=<?= $c['CommentID'] ?>" class="btn btn-sm btn-danger"
               onclick="return confirm('Delete this comment?')">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>