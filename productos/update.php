<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $componente_id = (int)($_POST['componente_id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $tipo_producto = trim($_POST['tipo_producto'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    if ($id === 0 || $componente_id === 0 || empty($nombre)) {
        die("Datos inválidos.");
    }

    try {
        $sql = "UPDATE productos SET componente_id = :componente_id, nombre = :nombre, tipo_producto = :tipo_producto, descripcion = :descripcion WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':componente_id' => $componente_id,
            ':nombre' => $nombre,
            ':tipo_producto' => $tipo_producto,
            ':descripcion' => $descripcion,
            ':id' => $id
        ]);
        
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        die("Error al actualizar el producto: " . $e->getMessage());
    }
} else {
    header('Location: index.php');
    exit;
}
?>