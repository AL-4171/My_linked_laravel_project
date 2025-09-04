<?php
require_once __DIR__ . '/../config.php';

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: index.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM tags WHERE TagID=?");
$stmt->execute([$id]);
$tag = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $pdo->prepare("UPDATE tags SET Name=? WHERE TagID=?")->execute([$name, $id]);

  header('Location: index.php');
  exit;
}

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>
<div class="container py-4">
  <h2>Edit Tag</h2>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($tag['Name']) ?>" required>
    </div>
    <button type="submit" class="btn btn-success">Update</button>
  </form>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>