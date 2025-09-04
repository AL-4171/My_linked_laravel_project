<?php
require_once __DIR__ . '/../config.php';

$id = $_GET['id'] ?? null;
if ($id) {
  $pdo->prepare("DELETE FROM profiles WHERE ProfileID=?")->execute([$id]);
}
header('Location: index.php');
exit;