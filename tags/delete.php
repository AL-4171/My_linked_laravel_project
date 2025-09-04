<?php
require_once __DIR__ . '/../config.php';

$id = $_GET['id'] ?? null;
if ($id) {
  $pdo->prepare("DELETE FROM posts_tags WHERE TagID=?")->execute([$id]);
  $pdo->prepare("DELETE FROM tags WHERE TagID=?")->execute([$id]);
}
header('Location: index.php');
exit;