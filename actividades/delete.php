<?php
require_once '../includes/config.php';

$id = (int)($_GET['id'] ?? 0);

if ($id === 0) {
    header('Location: index.php');
    exit;
}

try {
    $sql = "DELETE FROM actividades WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    
    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    header('Location: index.php?error=' . urlencode('No se puede eliminar la actividad porque tiene datos asociados (ej. beneficiarios).'));
    exit;
}
?>