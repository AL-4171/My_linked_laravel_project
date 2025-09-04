<?php
require_once __DIR__ . '/../config.php';

$userId  = $_SESSION['user']['UserID'] ?? null;
$isAdmin = strtolower($_SESSION['user']['Role'] ?? '') === 'admin';

if (!$userId) {
    header("Location: /Article-Web-main/auth/login.php");
    exit;
}

// Get Post ID
$postId = (int)($_GET['id'] ?? 0);
if (!$postId) {
    header("Location: /Article-Web-main/posts/myposts.php");
    exit;
}

// Fetch the post
$stmt = $pdo->prepare("SELECT * FROM posts WHERE PostID = ?");
$stmt->execute([$postId]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$post) {
    // No output yet to keep header-safe
    header("Location: /Article-Web-main/index.php");
    exit;
}

// Ownership check: admin can edit all, users only their own
if (!$isAdmin && $post['UserID'] != $userId) {
    header("HTTP/1.1 403 Forbidden");
    echo "You are not allowed to edit this post.";
    exit;
}

$error = null;

// Handle form submit BEFORE output to keep redirects safe
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title'] ?? '');
    $body  = trim($_POST['body'] ?? '');
    $catsInput = trim($_POST['categories'] ?? '');
    $tagsInput = trim($_POST['tags'] ?? '');

    // Parse comma-separated values
    $newCats = array_filter(array_map('trim', $catsInput === '' ? [] : explode(',', $catsInput)));
    $newTags = array_filter(array_map('trim', $tagsInput === '' ? [] : explode(',', $tagsInput)));

    if ($body !== '') {
        // Update post (Title is allowed to be empty per your requirement)
        $stmt = $pdo->prepare("UPDATE posts SET Title = ?, Body = ? WHERE PostID = ?");
        $stmt->execute([$title, $body, $postId]);

        // Reset and re-link categories (link to existing only)
        $pdo->prepare("DELETE FROM posts_categories WHERE PostID = ?")->execute([$postId]);
        if (!empty($newCats)) {
            $selCat = $pdo->prepare("SELECT CategoryID FROM categories WHERE Name = ?");
            $insPC  = $pdo->prepare("INSERT INTO posts_categories (CategoryID, PostID) VALUES (?, ?)");
            foreach ($newCats as $cName) {
                $selCat->execute([$cName]);
                $cid = $selCat->fetchColumn();
                if ($cid) {
                    $insPC->execute([$cid, $postId]);
                }
            }
        }

        // Reset and re-link tags (link to existing only)
        $pdo->prepare("DELETE FROM posts_tags WHERE PostID = ?")->execute([$postId]);
        if (!empty($newTags)) {
            $selTag = $pdo->prepare("SELECT TagID FROM tags WHERE Name = ?");
            $insPT  = $pdo->prepare("INSERT INTO posts_tags (TagID, PostID) VALUES (?, ?)");
            foreach ($newTags as $tName) {
                $selTag->execute([$tName]);
                $tid = $selTag->fetchColumn();
                if ($tid) {
                    $insPT->execute([$tid, $postId]);
                }
            }
        }

        // Redirect to view page of this post
        header("Location: /Article-Web-main/posts/view.php?id=" . $postId);
        exit;
    } else {
        $error = "Post body is required.";
    }
}

// ===== Fetch data for form (after POST handling) =====

// All categories/tags (for placeholder list)
$categoriesAll = $pdo->query("SELECT Name FROM categories ORDER BY Name")->fetchAll(PDO::FETCH_COLUMN);
$tagsAll       = $pdo->query("SELECT Name FROM tags ORDER BY Name")->fetchAll(PDO::FETCH_COLUMN);

// Current categories
$stmt = $pdo->prepare("
    SELECT c.Name
    FROM categories c
    JOIN posts_categories pc ON c.CategoryID = pc.CategoryID
    WHERE pc.PostID = ?
    ORDER BY c.Name
");
$stmt->execute([$postId]);
$currentCategories = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Current tags
$stmt = $pdo->prepare("
    SELECT t.Name
    FROM tags t
    JOIN posts_tags pt ON t.TagID = pt.TagID
    WHERE pt.PostID = ?
    ORDER BY t.Name
");
$stmt->execute([$postId]);
$currentTags = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Now it's safe to output HTML (navbar etc.)
include __DIR__ . '/../partials/navbar.php';
include __DIR__ . '/../partials/header.php';?>

<div class="container mt-4">
  <h2 class="mb-4"><i class="bi bi-pencil-square"></i> Edit Post</h2>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label class="form-label">Title</label>
      <input name="title" class="form-control" value="<?= htmlspecialchars($_POST['title'] ?? ($post['Title'] ?? '')) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Body</label>
      <textarea name="body" class="form-control" rows="6" required><?= htmlspecialchars($_POST['body'] ?? ($post['Body'] ?? '')) ?></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Categories (comma separated)</label>
      <?php if (!empty($categoriesAll)) { ?>
        <input
          name="categories"
          class="form-control"
          placeholder="Available: <?= htmlspecialchars(implode(', ', $categoriesAll)) ?>"
          value="<?= htmlspecialchars($_POST['categories'] ?? implode(', ', $currentCategories)) ?>"
        >
        <small class="text-muted">Only existing category names will be linked.</small>
      <?php } else { ?>
        <input type="text" class="form-control" value="No categories available (admin must create them)" disabled>
      <?php } ?>
    </div>

    <div class="mb-3">
      <label class="form-label">Tags (comma separated)</label>
      <?php if (!empty($tagsAll)) { ?>
        <input
          name="tags"
          class="form-control"
          placeholder="Available: <?= htmlspecialchars(implode(', ', $tagsAll)) ?>"
          value="<?= htmlspecialchars($_POST['tags'] ?? implode(', ', $currentTags)) ?>"
        >
        <small class="text-muted">Only existing tag names will be linked.</small>
      <?php } else { ?>
        <input type="text" class="form-control" value="No tags available (admin must create them)" disabled>
      <?php } ?>
    </div>

    <button class="btn btn-success"><i class="bi bi-save"></i> Update</button>
  </form>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>