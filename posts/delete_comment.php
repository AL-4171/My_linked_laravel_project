<?php
require_once __DIR__ . '/../config.php';
if (!isset($_SESSION['user'])) { header("Location: ../auth/login.php"); exit; }

$comment_id = (int)($_POST['id'] ?? 0);
$post_id = (int)($_POST['post_id'] ?? 0);

$stmt = $pdo->prepare("SELECT UserID FROM comments WHERE CommentID=?");
$stmt->execute([$comment_id]);
$c = $stmt->fetch();

if ($c) {
    if ($_SESSION['user']['Role'] === 'admin' || $_SESSION['user']['UserID'] == $c['UserID']) {
        $pdo->prepare("DELETE FROM comments WHERE CommentID=?")->execute([$comment_id]);
    }
}
header("Location: view.php?id=$post_id");
exit;