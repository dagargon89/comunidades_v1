<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $eje_id = (int)($_POST['eje_id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    if ($id === 0 || $eje_id === 0 || empty($nombre)) {
        die("Datos inválidos.");
    }

    try {
        $sql = "UPDATE componentes SET eje_id = :eje_id, nombre = :nombre, descripcion = :descripcion WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':eje_id' => $eje_id,
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':id' => $id
        ]);
        
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        die("Error al actualizar el componente: " . $e->getMessage());
    }
} else {
    header('Location: index.php');
    exit;
}
?>