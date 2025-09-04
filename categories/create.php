<?php
require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $desc = $_POST['description'];

  $pdo->prepare("INSERT INTO categories (Name, Description) VALUES (?, ?)")
      ->execute([$name, $desc]);

  header('Location: index.php');
  exit;
}

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>
<div class="container py-4">
  <h2>Create Category</h2>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text" class="form-control" name="name" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea class="form-control" name="description"></textarea>
    </div>
    <button type="submit" class="btn btn-success">Save</button>
  </form>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>