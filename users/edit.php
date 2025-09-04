<?php
require_once __DIR__ . '/../config.php';
include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['Role']!=='admin') die("Access denied");

$id=(int)($_GET['id']??0);
$stmt = $pdo->prepare("SELECT * FROM users WHERE UserID=?");
$stmt->execute([$id]); $u = $stmt->fetch();
if (!$u) die("Not found");

if ($_SERVER['REQUEST_METHOD']==='POST'){
  $name = trim($_POST['Name']); $email=trim($_POST['Email']); $age=$_POST['Age']?:null; $phone=$_POST['Phone']?:null; $role=$_POST['Role'] ?? 'user';
  if (!empty($_POST['pass'])) {
    $hash = password_hash($_POST['pass'],PASSWORD_DEFAULT);
    $pdo->prepare("UPDATE users SET Name=?,Email=?,pass=?,Age=?,Phone=?,Role=? WHERE UserID=?")->execute([$name,$email,$hash,$age,$phone,$role,$id]);
  } else {
    $pdo->prepare("UPDATE users SET Name=?,Email=?,Age=?,Phone=?,Role=? WHERE UserID=?")->execute([$name,$email,$age,$phone,$role,$id]);
  }
  header('Location:index.php'); exit;
}
?>
<div class="container py-5">
  <h2>Edit User</h2>
  <form method="post">
    <div class="mb-3"><label>Name</label><input name="Name" class="form-control" required value="<?= htmlspecialchars($u['Name']) ?>"></div>
    <div class="mb-3"><label>Email</label><input name="Email" class="form-control" required value="<?= htmlspecialchars($u['Email']) ?>"></div>
    <div class="mb-3"><label>New password (leave blank to keep)</label><input name="pass" class="form-control" type="password"></div>
    <div class="mb-3"><label>Age</label><input name="Age" class="form-control" type="number" value="<?= $u['Age'] ?>"></div>
    <div class="mb-3"><label>Phone</label><input name="Phone" class="form-control" value="<?= htmlspecialchars($u['Phone']) ?>"></div>
    <div class="mb-3"><label>Role</label>
      <select name="Role" class="form-select">
        <option value="user" <?= $u['Role']==='user'?'selected':'' ?>>user</option>
        <option value="admin" <?= $u['Role']==='admin'?'selected':'' ?>>admin</option>
      </select>
    </div>
    <button class="btn btn-primary">Save</button>
  </form>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>