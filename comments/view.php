<?php
require_once __DIR__ . '/../config.php';

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: index.php'); exit; }

$stmt = $pdo->prepare("
  SELECT c.CommentID, c.Content, c.CreatedAt,
         u.Name AS UserName, p.Title AS PostTitle
  FROM comments c
  JOIN users u ON c.UserID = u.UserID
  JOIN posts p ON c.PostID = p.PostID
  WHERE c.CommentID=?
");
$stmt->execute([$id]);
$comment = $stmt->fetch();

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>
<div class="container py-4">
  <h2>Comment Details</h2>
  <p><b>User:</b> <?= htmlspecialchars($comment['UserName']) ?></p>
  <p><b>Post:</b> <?= htmlspecialchars($comment['PostTitle']) ?></p>
  <p><b>Content:</b> <?= nl2br(htmlspecialchars($comment['Content'])) ?></p>
  <p><b>Created At:</b> <?= $comment['CreatedAt'] ?></p>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>