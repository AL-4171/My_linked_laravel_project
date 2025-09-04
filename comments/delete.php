<?php
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);

// fetch comment
$stmt = $pdo->prepare("SELECT * FROM comments WHERE CommentID=?");
$stmt->execute([$id]);
$comment = $stmt->fetch();

if (!$comment) {
    die("Comment not found.");
}

// 🔒 allow only admin or comment owner
if ($_SESSION['user']['Role'] !== 'admin' && $_SESSION['user']['UserID'] != $comment['UserID']) {
    die("Access denied. You cannot delete this comment.");
}

// delete comment
$pdo->prepare("DELETE FROM comments WHERE CommentID=?")->execute([$id]);

// redirect back to post page
header("Location: ../posts/view.php?id=" . $comment['PostID']);
exit;