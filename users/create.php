<?php
require_once __DIR__ . '/../config.php';
include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['Role']!=='admin') die("Access denied");

$err = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $name = trim($_POST['Name'] ?? '');
    $email = trim($_POST['Email'] ?? '');
    $pass = $_POST['pass'] ?? '';
    $age = $_POST['Age']?:null;
    $phone = $_POST['Phone']?:null;
    $role = $_POST['Role'] ?? 'user';

    if (!$name || !$email || !$pass) $err='Name, email and password required';
    else {
      $hash = password_hash($pass, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("INSERT INTO users (Name, Email, pass, Age, Phone, Role) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->execute([$name,$email,$hash,$age,$phone,$role]);
      header('Location: index.php'); exit;
    }
}
?>
<div class="container py-5">
  <h2>Create User</h2>
  <?php if ($err) echo "<div class='alert alert-danger'>".htmlspecialchars($err)."</div>"; ?>
  <form method="post">
    <div class="mb-3"><label>Name</label><input name="Name" class="form-control" required></div>
    <div class="mb-3"><label>Email</label><input name="Email" class="form-control" type="email" required></div>
    <div class="mb-3"><label>Password</label><input name="pass" class="form-control" type="password" required></div>
    <div class="mb-3"><label>Age</label><input name="Age" class="form-control" type="number"></div>
    <div class="mb-3"><label>Phone</label><input name="Phone" class="form-control"></div>
    <div class="mb-3"><label>Role</label>
      <select name="Role" class="form-select"><option value="user">user</option><option value="admin">admin</option></select>
    </div>
    <button class="btn btn-success">Create</button>
  </form>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>