<?php
require_once __DIR__ . '/_guard.php';
require_once __DIR__ . '/../includes/db.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id>0) {
  $pdo->prepare("DELETE FROM users WHERE id=?")->execute([$id]);
}
header('Location: dashboard.php'); exit;
