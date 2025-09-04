<?php
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['Content'] ?? '');
    $userId  = $_SESSION['user']['UserID'];   // from session
    $postId  = (int)($_GET['post_id'] ?? 0);  // from query param in form action

    if ($content && $postId > 0) {
        $stmt = $pdo->prepare("INSERT INTO comments (Content, CreatedAt, UserID, PostID) VALUES (?, NOW(), ?, ?)");
        $stmt->execute([$content, $userId, $postId]);
    }

    header("Location: ../posts/view.php?id=" . $postId);
    exit;
}