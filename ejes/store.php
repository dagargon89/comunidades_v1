<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    if (empty($nombre)) {
        die("El nombre es obligatorio.");
    }

    try {
        $sql = "INSERT INTO ejes (nombre, descripcion) VALUES (:nombre, :descripcion)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':nombre' => $nombre, ':descripcion' => $descripcion]);
        
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        die("Error al guardar el eje: " . $e->getMessage());
    }
} else {
    header('Location: index.php');
    exit;
}
?>