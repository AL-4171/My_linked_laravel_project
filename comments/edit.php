<?php
require_once __DIR__ . '/../config.php';

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: index.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM comments WHERE CommentID=?");
$stmt->execute([$id]);
$comment = $stmt->fetch();

$users = $pdo->query("SELECT UserID, Name FROM users")->fetchAll();
$posts = $pdo->query("SELECT PostID, Title FROM posts")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $content = $_POST['content'];
  $user_id = $_POST['user_id'];
  $post_id = $_POST['post_id'];

  $pdo->prepare("UPDATE comments SET Content=?, UserID=?, PostID=? WHERE CommentID=?")
      ->execute([$content, $user_id, $post_id, $id]);

  header('Location: index.php');
  exit;
}

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>
<div class="container py-4">
  <h2>Edit Comment</h2>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">User</label>
      <select name="user_id" class="form-control" required>
        <?php foreach ($users as $u): ?>
          <option value="<?= $u['UserID'] ?>" <?= $u['UserID']==$comment['UserID'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($u['Name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Post</label>
      <select name="post_id" class="form-control" required>
        <?php foreach ($posts as $p): ?>
          <option value="<?= $p['PostID'] ?>" <?= $p['PostID']==$comment['PostID'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($p['Title']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Content</label>
      <textarea name="content" class="form-control" rows="3" required><?= htmlspecialchars($comment['Content']) ?></textarea>
    </div>
    <button type="submit" class="btn btn-success">Update</button>
  </form>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>