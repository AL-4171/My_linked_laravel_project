<?php
require_once __DIR__ . '/../config.php';

$id = $_GET['id'] ?? null;
if ($id) {
  $pdo->prepare("DELETE FROM posts_categories WHERE CategoryID=?")->execute([$id]);
  $pdo->prepare("DELETE FROM categories WHERE CategoryID=?")->execute([$id]);
}
header('Location: index.php');
exit;