<?php
require_once __DIR__ . '/../config.php';

$users = $pdo->query("SELECT UserID, Name FROM users")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $bio = $_POST['bio'];
  $user_id = $_POST['user_id'];

  $pdo->prepare("INSERT INTO profiles (Bio, UserID) VALUES (?, ?)")->execute([$bio, $user_id]);

  header('Location: index.php');
  exit;
}

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>
<div class="container py-4">
  <h2>Create Profile</h2>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">User</label>
      <select name="user_id" class="form-control" required>
        <option value="">-- Select User --</option>
        <?php foreach ($users as $u): ?>
          <option value="<?= $u['UserID'] ?>"><?= htmlspecialchars($u['Name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Bio</label>
      <textarea name="bio" class="form-control" rows="3" required></textarea>
    </div>
    <button type="submit" class="btn btn-success">Save</button>
  </form>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>