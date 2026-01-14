<?php
require_once __DIR__ . '/../_guard.php';
require_once __DIR__ . '/../../includes/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header('Location: ../dashboard.php'); exit; }

$stmt = $pdo->prepare("DELETE FROM skills WHERE id = ?");
$stmt->execute([$id]);

header('Location: ../dashboard.php?deleted=1');
exit;
