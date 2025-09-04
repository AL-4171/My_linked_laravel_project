<?php
require_once __DIR__ . '/../config.php';

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: index.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM tags WHERE TagID=?");
$stmt->execute([$id]);
$tag = $stmt->fetch();

$posts = $pdo->prepare("
  SELECT p.PostID, p.Body, p.CreatedAt, u.Name AS Author
  FROM posts p
  JOIN users u ON p.UserID = u.UserID
  JOIN posts_tags pt ON p.PostID = pt.PostID
  WHERE pt.TagID=?
");
$posts->execute([$id]);
$posts = $posts->fetchAll();

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>
<div class="container py-4">
  <h2>Tag Details</h2>
  <p><b>Name:</b> <?= htmlspecialchars($tag['Name']) ?></p>

  <hr>
  <h4>Posts with this Tag</h4>
  <?php foreach ($posts as $p): ?>
    <div class="border rounded p-2 mb-2">
      <p><?= htmlspecialchars(substr($p['Body'],0,100)) ?>...</p>
      <small>By <?= htmlspecialchars($p['Author']) ?> on <?= $p['CreatedAt'] ?></small><br>
      <a href="../posts/view.php?id=<?= $p['PostID'] ?>" class="btn btn-sm btn-primary">Read</a>
    </div>
  <?php endforeach; ?>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>