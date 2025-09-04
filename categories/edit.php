<?php
require_once __DIR__ . '/../config.php';

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: index.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM categories WHERE CategoryID=?");
$stmt->execute([$id]);
$category = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $desc = $_POST['description'];

  $pdo->prepare("UPDATE categories SET Name=?, Description=? WHERE CategoryID=?")
      ->execute([$name, $desc, $id]);

  header('Location: index.php');
  exit;
}

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>
<div class="container py-4">
  <h2>Edit Category</h2>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($category['Name']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea class="form-control" name="description"><?= htmlspecialchars($category['Description']) ?></textarea>
    </div>
    <button type="submit" class="btn btn-success">Update</button>
  </form>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>