<?php
require_once __DIR__ . '/config.php';


if (!isset($_SESSION['user'])) {
    header('Location: auth/login.php');
    exit;
}


$sql = "
    SELECT p.PostID, p.Title, p.Body, p.CreatedAt,
           u.Name AS Author,
           GROUP_CONCAT(DISTINCT c.Name SEPARATOR ', ') AS Categories,
           GROUP_CONCAT(DISTINCT t.Name SEPARATOR ', ') AS Tags
    FROM posts p
    JOIN users u ON p.UserID = u.UserID
    LEFT JOIN posts_categories pc ON p.PostID = pc.PostID
    LEFT JOIN categories c ON pc.CategoryID = c.CategoryID
    LEFT JOIN posts_tags pt ON p.PostID = pt.PostID
    LEFT JOIN tags t ON pt.TagID = t.TagID
    GROUP BY p.PostID
    ORDER BY p.CreatedAt DESC
";
$stmt = $pdo->query($sql);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/partials/header.php';
include __DIR__ . '/partials/navbar.php';
?>

<div class="container py-5">
  <div class="row g-4">

    <?php if (count($posts) > 0): ?>
      <?php foreach ($posts as $post): ?>
        <div class="col-md-4">
          <div class="card rounded-3 shadow-sm h-100">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($post['Title']) ?></h5>
              <p class="card-text">
                <?= nl2br(htmlspecialchars(substr($post['Body'], 0, 120))) ?>...
              </p>
              <p class="text-muted"><small>By <?= htmlspecialchars($post['Author']) ?></small></p>

              <?php if ($post['Categories']): ?>
                <p><i class="bi bi-folder"></i>
                  <small><?= htmlspecialchars($post['Categories']) ?></small>
                </p>
              <?php endif; ?>

              <?php if ($post['Tags']): ?>
                <p><i class="bi bi-tags"></i>
                  <small><?= htmlspecialchars($post['Tags']) ?></small>
                </p>
              <?php endif; ?>

              <a href="posts/view.php?id=<?= $post['PostID'] ?>" class="btn btn-primary mt-auto">See More</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12">
        <div class="alert alert-info text-center">No posts available yet.</div>
      </div>
    <?php endif; ?>

  </div>
</div>


<?php include __DIR__ . '/partials/footer.php'; ?>