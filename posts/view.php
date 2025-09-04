<?php
require_once __DIR__ . '/../config.php';
include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';

$id = (int)($_GET['id'] ?? 0);

// fetch post
$stmt = $pdo->prepare("
    SELECT p.PostID, p.Title, p.Body, p.CreatedAt,
           u.Name AS Author
    FROM posts p
    JOIN users u ON p.UserID = u.UserID
    WHERE p.PostID = ?
");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    echo "<div class='container py-5'><div class='alert alert-danger'>Post not found.</div></div>";
    include __DIR__ . '/../partials/footer.php'; exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    if (!isset($_SESSION['user'])) {
        header("Location: ../auth/login.php"); exit;
    }
    $content = trim($_POST['comment']);
    if ($content) {
        $stmt = $pdo->prepare("INSERT INTO comments (Content, CreatedAt, UserID, PostID) VALUES (?, NOW(), ?, ?)");
        $stmt->execute([$content, $_SESSION['user']['UserID'], $id]);
    }
    header("Location: view.php?id=$id"); exit;
}

// fetch comments
$stmt = $pdo->prepare("
    SELECT c.CommentID, c.Content, c.CreatedAt, u.Name, u.UserID
    FROM comments c
    JOIN users u ON c.UserID = u.UserID
    WHERE c.PostID = ?
    ORDER BY c.CreatedAt DESC
");
$stmt->execute([$id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-4">
  <h2><?= htmlspecialchars($post['Title']) ?></h2>
  <p class="post-body"><?= nl2br(htmlspecialchars($post['Body'])) ?></p>
  <p class="text-muted"><small>By <?= htmlspecialchars($post['Author']) ?> on <?= $post['CreatedAt'] ?></small></p>
  
  <hr>
  <h4 class="mt-4">Comments</h4>

<?php
$stmt = $pdo->prepare("
    SELECT c.CommentID, c.Content, c.CreatedAt, u.Name, u.UserID
    FROM comments c
    JOIN users u ON c.UserID = u.UserID
    WHERE c.PostID=?
    ORDER BY c.CreatedAt DESC
");
$stmt->execute([$id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if ($comments): ?>
  <?php foreach ($comments as $comment): ?>
    <div class="border rounded p-2 mb-2">
      <p><?= htmlspecialchars($comment['Content']) ?></p>
      <small class="text-muted">
        By <?= htmlspecialchars($comment['Name']) ?> at <?= $comment['CreatedAt'] ?>
      </small>

      <?php if (
          $_SESSION['user']['Role'] === 'admin' || 
          $_SESSION['user']['UserID'] == $comment['UserID']
      ): ?>
        <a href="../comments/delete.php?id=<?= $comment['CommentID'] ?>" 
           class="btn btn-sm btn-danger ms-2"
           onclick="return confirm('Are you sure you want to delete this comment?');">
          <i class="bi bi-trash"></i>
        </a>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
<?php else: ?>
  <p>No comments yet.</p>
<?php endif; ?>

<!-- Add Comment Form -->
<form method="post" action="../comments/create.php?post_id=<?= $id ?>" class="mt-3">
  <div class="mb-3">
    <textarea name="Content" class="form-control" placeholder="Write a comment..." required></textarea>
  </div>
  <button class="btn btn-primary">Add Comment</button>
</form>
 
 
  
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
