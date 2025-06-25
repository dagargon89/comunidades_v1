<?php
require_once '../includes/config.php';
$id = (int)($_GET['id'] ?? 0);
if ($id === 0) { header('Location: index.php'); exit; }
try {
    $sql = "DELETE FROM organizaciones WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    header('Location: index.php?error=' . urlencode('No se puede eliminar, usuarios dependen de esta organización.'));
    exit;
}
?>