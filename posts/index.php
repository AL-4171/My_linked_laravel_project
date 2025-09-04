<?php
require_once __DIR__ . '/../config.php';

$stmt = $pdo->query("
  SELECT p.PostID, p.Body, p.CreatedAt, u.Name AS Author,
         GROUP_CONCAT(DISTINCT c.Name SEPARATOR ', ') AS Categories,
         GROUP_CONCAT(DISTINCT t.Name SEPARATOR ', ') AS Tags
  FROM posts p
  JOIN users u ON p.UserID = u.UserID
  LEFT JOIN posts_categories pc ON p.PostID = pc.PostID
  LEFT JOIN categories c ON pc.CategoryID = c.CategoryID
  LEFT JOIN posts_tags pt ON p.PostID = pt.PostID
  LEFT JOIN tags t ON pt.TagID = t.TagID
  GROUP BY p.PostID
  ORDER BY p.CreatedAt DESC
");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>
<div class="container py-4">
  <h2>All Posts</h2>
  <a href="create.php" class="btn btn-success mb-3">+ Create Post</a>
  <table class="table table-bordered">
    <thead><tr><th>Title</th><th>Author</th><th>Categories</th><th>Tags</th><th>Created</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($posts as $post): ?>
        <tr>
          <td><?= htmlspecialchars(substr($post['Body'],0,30)) ?>...</td>
          <td><?= htmlspecialchars($post['Author']) ?></td>
          <td><?= htmlspecialchars($post['Categories']) ?></td>
          <td><?= htmlspecialchars($post['Tags']) ?></td>
          <td><?= $post['CreatedAt'] ?></td>
          <td>
            <a href="view.php?id=<?= $post['PostID'] ?>" class="btn btn-sm btn-primary">View</a>
            <a href="edit.php?id=<?= $post['PostID'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="delete.php?id=<?= $post['PostID'] ?>" class="btn btn-sm btn-danger"
               onclick="return confirm('Delete this post?')">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>