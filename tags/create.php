<?php
require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];

  $pdo->prepare("INSERT INTO tags (Name) VALUES (?)")->execute([$name]);

  header('Location: index.php');
  exit;
}

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>
<div class="container py-4">
  <h2>Create Tag</h2>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text" class="form-control" name="name" required>
    </div>
    <button type="submit" class="btn btn-success">Save</button>
  </form>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>