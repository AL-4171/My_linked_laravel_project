<?php
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);
$user = $_SESSION['user'];

// fetch post
$stmt = $pdo->prepare("SELECT * FROM posts WHERE PostID=?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    die("Post not found.");
}

// check permission (admin OR owner)
if ($user['Role'] !== 'admin' && $user['UserID'] != $post['UserID']) {
    die("❌ You do not have permission to delete this post.");
}

// delete post + relations
$pdo->prepare("DELETE FROM posts_categories WHERE PostID=?")->execute([$id]);
$pdo->prepare("DELETE FROM posts_tags WHERE PostID=?")->execute([$id]);
$pdo->prepare("DELETE FROM comments WHERE PostID=?")->execute([$id]);
$pdo->prepare("DELETE FROM posts WHERE PostID=?")->execute([$id]);

header("Location: myposts.php");
exit;