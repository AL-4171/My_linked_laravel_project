<?php
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user = $_SESSION['user'];

// fetch posts belonging to this user
$stmt = $pdo->prepare("
    SELECT p.*, u.Name AS AuthorName 
    FROM posts p
    JOIN users u ON p.UserID = u.UserID
    WHERE p.UserID = ?
    ORDER BY p.CreatedAt DESC
");
$stmt->execute([$user['UserID']]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>

<div class="container py-5">
  <h2><i class="bi bi-file-earmark-person"></i> My Posts</h2>

  <?php if (!$posts): ?>
    <div class="alert alert-info mt-4">You haven’t written any posts yet.</div>
  <?php else: ?>
    <div class="list-group mt-4">
      <?php foreach ($posts as $post): ?>
        <div class="list-group-item">
          <h5 class="mb-1"><?= htmlspecialchars($post['Title']) ?></h5>
          <small>By <?= htmlspecialchars($post['AuthorName']) ?> on <?= htmlspecialchars($post['CreatedAt']) ?></small>
          <div class="mt-2">
            <a href="/Article-Web-main/posts/view.php?id=<?= $post['PostID'] ?>" class="btn btn-sm btn-outline-primary">
              <i class="bi bi-eye"></i> View
            </a>
            <?php if ($user['Role'] === 'admin' || $user['UserID'] == $post['UserID']): ?>
              <a href="/Article-Web-main/posts/edit.php?id=<?= $post['PostID'] ?>" class="btn btn-sm btn-outline-warning">
                <i class="bi bi-pencil"></i> Edit
              </a>
              <a href="/Article-Web-main/posts/delete.php?id=<?= $post['PostID'] ?>" 
                 class="btn btn-sm btn-outline-danger" 
                 onclick="return confirm('Are you sure you want to delete this post?')">
                <i class="bi bi-trash"></i> Delete
              </a>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>