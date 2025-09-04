<?php
require_once __DIR__ . '/../config.php';
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['Role']!=='admin') die("Access denied");
$id=(int)($_GET['id']??0);
if ($id) {
  $pdo->prepare("DELETE FROM profiles WHERE UserID=?")->execute([$id]);
  $pdo->prepare("DELETE FROM comments WHERE UserID=?")->execute([$id]);
  $pdo->prepare("DELETE FROM posts WHERE UserID=?")->execute([$id]);
  $pdo->prepare("DELETE FROM users WHERE UserID=?")->execute([$id]);
}
header('Location:index.php'); exit;