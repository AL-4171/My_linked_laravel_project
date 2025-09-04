<?php
require_once __DIR__ . '/../config.php';


if (!isset($_SESSION['user'])) {
    header("Location: /Article-Web-main/auth/login.php");
    exit;
}

$userId = $_SESSION['user']['UserID'] ?? null;


$categories = $pdo->query("SELECT CategoryID, Name FROM categories ORDER BY Name")
                  ->fetchAll(PDO::FETCH_ASSOC);


$tags = $pdo->query("SELECT TagID, Name FROM tags ORDER BY Name")
            ->fetchAll(PDO::FETCH_ASSOC);


function createPost($pdo, $userId, $title, $body, $categoryIds, $tagIds) {

    $stmt = $pdo->prepare("
        INSERT INTO posts (UserID, Title, Body, CreatedAt)
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->execute([$userId, $title, $body]);
    $postId = $pdo->lastInsertId();

  
    if (!empty($categoryIds)) {
        $stmtCat = $pdo->prepare("INSERT INTO posts_categories (CategoryID, PostID) VALUES (?, ?)");
        foreach ($categoryIds as $catId) {
            $stmtCat->execute([$catId, $postId]);
        }
    }

    
    if (!empty($tagIds)) {
        $stmtTag = $pdo->prepare("INSERT INTO posts_tags (TagID, PostID) VALUES (?, ?)");
        foreach ($tagIds as $tagId) {
            $stmtTag->execute([$tagId, $postId]);
        }
    }

    return $postId;
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title       = trim($_POST['title'] ?? '');
    $body        = trim($_POST['body'] ?? '');
    $categoryIds = $_POST['categories'] ?? [];
    $tagIds      = $_POST['tags'] ?? [];

    if ($body && $userId) {
        createPost($pdo, $userId, $title, $body, $categoryIds, $tagIds);
        header("Location: /Article-Web-main/posts/myposts.php");
        exit;
    } else {
        $error = "Post body is required.";
    }
}
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/navbar.php'; ?>

<div class="container">
    <h2 class="mb-3">Create Post</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" placeholder="Enter a title " required>
        </div>

        <div class="mb-3">
            <label class="form-label">Body</label>
            <textarea name="body" class="form-control" rows="5" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Categories</label>
            <?php if (!empty($categories)) { ?>
                <select name="categories[]" class="form-select" multiple>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['CategoryID'] ?>"><?= htmlspecialchars($cat['Name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="text-muted">Hold Ctrl (Cmd on Mac) to select multiple</small>
            <?php } else { ?>
                <input type="text" class="form-control" value="No categories available (admin must create them)" disabled>
            <?php } ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Tags</label>
            <?php if (!empty($tags)) { ?>
                <select name="tags[]" class="form-select" multiple>
                    <?php foreach ($tags as $tag): ?>
                        <option value="<?= $tag['TagID'] ?>"><?= htmlspecialchars($tag['Name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="text-muted">Hold Ctrl (Cmd on Mac) to select multiple</small>
            <?php } else { ?>
                <input type="text" class="form-control" value="No tags available (admin must create them)" disabled>
            <?php } ?>
        </div>

        <button type="submit" class="btn btn-success">
            <i class="bi bi-publish"></i> Publish
        </button>
    </form>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>