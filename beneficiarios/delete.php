<?php
require_once '../includes/config.php';

$id = (int)($_GET['id'] ?? 0);

if ($id === 0) {
    header('Location: index.php');
    exit;
}

try {
    // Primero verificamos que el beneficiario existe
    $stmt_check = $pdo->prepare("SELECT id FROM beneficiarios WHERE id = ?");
    $stmt_check->execute([$id]);
    
    if ($stmt_check->fetch()) {
        // Si existe, procedemos a eliminarlo
        $stmt_delete = $pdo->prepare("DELETE FROM beneficiarios WHERE id = ?");
        $stmt_delete->execute([$id]);
    }
    
    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    // Si hay un error de clave foránea (el beneficiario está en una actividad),
    // la base de datos lo impedirá. Redirigimos con un mensaje de error.
    header('Location: index.php?error=' . urlencode('No se puede eliminar el beneficiario porque está asignado a una o más actividades.'));
    exit;
}
?>