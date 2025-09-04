<?php
require_once __DIR__ . '/../config.php'; 
$user    = $_SESSION['user'] ?? null;
$isAdmin = $user && strtolower($user['Role'] ?? '') === 'admin';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="/Article-Web-main/index.php">
      <i class="bi bi-journal-text"></i> BlogSystem
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
            aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMain">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="/Article-Web-main/index.php"><i class="bi bi-collection"></i> All Posts</a>
        </li>

        <?php if ($user) { ?>
          <li class="nav-item">
            <a class="nav-link" href="/Article-Web-main/posts/myposts.php"><i class="bi bi-file-earmark-person"></i> My Posts</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/Article-Web-main/posts/create.php"><i class="bi bi-plus-circle"></i> Create Post</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/Article-Web-main/profiles/view.php?id=<?= (int)$user['UserID'] ?>">
              <i class="bi bi-person-circle"></i> My Profile
            </a>
          </li>
        <?php } ?>
      </ul>

      <ul class="navbar-nav ms-auto">
        <?php if (!$user) { ?>
          <li class="nav-item">
            <a class="nav-link" href="/Article-Web-main/auth/login.php"><i class="bi bi-box-arrow-in-right"></i> Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/Article-Web-main/auth/register.php"><i class="bi bi-person-plus"></i> Sign Up</a>
          </li>
        <?php } else { ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-badge"></i> <?= htmlspecialchars($user['Name'] ?? $user['Email'] ?? 'User') ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="/Article-Web-main/posts/create.php"><i class="bi bi-plus-circle"></i> Create Post</a></li>
              <li><a class="dropdown-item" href="/Article-Web-main/auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="/Article-Web-main/auth/delete_account.php"><i class="bi bi-trash"></i> Delete Account</a></li>
            </ul>
          </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>

<?php if ($isAdmin) { ?>

  <div class="offcanvas offcanvas-start bg-light" tabindex="-1" id="adminSidebar" aria-labelledby="adminSidebarLabel" style="top:56px;">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title fw-bold" id="adminSidebarLabel">
        <i class="bi bi-speedometer2"></i> Admin Dashboard
      </h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <ul class="list-group">
        <a href="/Article-Web-main/posts/index.php" class="list-group-item"><i class="bi bi-file-text"></i> Manage Posts</a>
        <a href="/Article-Web-main/users/index.php" class="list-group-item"><i class="bi bi-people"></i> Manage Users</a>
        <a href="/Article-Web-main/profiles/index.php" class="list-group-item"><i class="bi bi-person-badge"></i> Manage Profiles</a>
        <a href="/Article-Web-main/comments/index.php" class="list-group-item"><i class="bi bi-chat-dots"></i> Manage Comments</a>
        <a href="/Article-Web-main/tags/index.php" class="list-group-item"><i class="bi bi-tags"></i> Manage Tags</a>
        <a href="/Article-Web-main/categories/index.php" class="list-group-item"><i class="bi bi-folder"></i> Manage Categories</a>
      </ul>
    </div>
  </div>

  <!-- Floating Dashboard button -->
  <button id="sidebarToggle"
          class="btn btn-primary shadow dashboard-btn"
          type="button"
          data-bs-toggle="offcanvas"
          data-bs-target="#adminSidebar"
          aria-controls="adminSidebar"
          title="Open admin dashboard">
    ☰ Dashboard
  </button>

  <style>
    /* Floating Dashboard button styling */
    .dashboard-btn {
      position: fixed;
      top: 72px;
      left: 20px;
      z-index: 2000;
      padding: 20px 18px;
      border-radius: 10px;
    }

    /* Ensure page content has space */
    body.has-admin-dashboard main,
    body.has-admin-dashboard .container {
      margin-top: 60px;  
      margin-bottom: 20px; /* push content slightly down */
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var toggleBtn = document.getElementById('sidebarToggle');
      var offcanvasEl = document.getElementById('adminSidebar');
      document.body.classList.add("has-admin-dashboard");

      if (!offcanvasEl || !toggleBtn) return;

      offcanvasEl.addEventListener('show.bs.offcanvas', function () {
        toggleBtn.style.display = 'none';
      });
      offcanvasEl.addEventListener('hidden.bs.offcanvas', function () {
        toggleBtn.style.display = 'inline-block';
      });
    });
  </script>
<?php } ?>