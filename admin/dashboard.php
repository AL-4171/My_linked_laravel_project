<?php
require_once __DIR__ . '/../config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['Role'] !== 'admin') {
    die("Access denied");
}

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';
?>
<div class="container-fluid">
  <div class="row">

    <div class="col-md-10 p-4">
      <h2  style="text-align:center;">Welcome Admin, <?= htmlspecialchars($_SESSION['user']['Name']) ?></h2>
      <p  style="text-align:center;">Select an entity from the sidebar to manage.</p>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>