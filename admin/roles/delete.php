<?php
require_once '../../includes/config.php';
requireAuth();
requireRole('admin');

$id = (int)($_GET['id'] ?? 0);
if ($id === 0) { header('Location: index.php'); exit; }

try {
    $sql = "DELETE FROM roles WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    header('Location: index.php?error=' . urlencode('No se puede eliminar el rol porque hay usuarios asignados a él.'));
    exit;
}
?>