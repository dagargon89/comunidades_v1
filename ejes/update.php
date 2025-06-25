<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    if ($id === 0 || empty($nombre)) {
        die("Datos inválidos.");
    }

    try {
        $sql = "UPDATE ejes SET nombre = :nombre, descripcion = :descripcion WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':nombre' => $nombre, ':descripcion' => $descripcion, ':id' => $id]);
        
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        die("Error al actualizar el eje: " . $e->getMessage());
    }
} else {
    header('Location: index.php');
    exit;
}
?>