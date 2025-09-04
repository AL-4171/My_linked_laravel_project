<?php
require_once __DIR__ . '/../config.php';
include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
if (!isset($_SESSION['user'])||$_SESSION['user']['Role']!=='admin') die("Access denied");

$cats = $pdo->query("SELECT * FROM categories ORDER BY Name")->fetchAll();
?>
<div class="container py-5">
  <h2>Categories</h2>
  <a class="btn btn-success mb-3" href="create.php">Create Category</a>
  <ul class="list-group">
    <?php foreach($cats as $c): ?>
      <li class="list-group-item d-flex justify-content-between">
        <div><strong><?= htmlspecialchars($c['Name']) ?></strong><div class="small"><?= htmlspecialchars($c['Description']) ?></div></div>
        <div>
          <a class="btn btn-sm btn-warning" href="edit.php?id=<?= $c['CategoryID'] ?>">Edit</a>
          <a class="btn btn-sm btn-danger" href="delete.php?id=<?= $c['CategoryID'] ?>" onclick="return confirm('Delete category?')">Delete</a>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>