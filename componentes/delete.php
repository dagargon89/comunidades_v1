<?php
require_once '../includes/config.php';

$id = (int)($_GET['id'] ?? 0);

if ($id === 0) {
    header('Location: index.php');
    exit;
}

try {
    $sql = "DELETE FROM componentes WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    
    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    // Si hay un error de clave foránea (porque un producto depende de este componente),
    // la base de datos lo impedirá. Redirigimos con un mensaje.
    header('Location: index.php?error=' . urlencode('No se puede eliminar el componente porque tiene productos asociados.'));
    exit;
}
?>