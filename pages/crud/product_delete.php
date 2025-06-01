<?php
session_start();
require '/opt/lampp/htdocs/electro/pages/includes/pdo.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../pages/login.php');
    exit();
}

$id = intval($_GET['id'] ?? 0);
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: /electro/pages/dashboard.php');
exit();
