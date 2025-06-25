<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $componente_id = (int)($_POST['componente_id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $tipo_producto = trim($_POST['tipo_producto'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    if (empty($nombre) || $componente_id === 0) {
        die("El nombre y el componente padre son obligatorios.");
    }

    try {
        $sql = "INSERT INTO productos (componente_id, nombre, tipo_producto, descripcion) VALUES (:componente_id, :nombre, :tipo_producto, :descripcion)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':componente_id' => $componente_id,
            ':nombre' => $nombre,
            ':tipo_producto' => $tipo_producto,
            ':descripcion' => $descripcion
        ]);
        
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        die("Error al guardar el producto: " . $e->getMessage());
    }
} else {
    header('Location: index.php');
    exit;
}
?>