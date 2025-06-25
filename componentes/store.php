<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eje_id = (int)($_POST['eje_id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    if (empty($nombre) || $eje_id === 0) {
        die("El nombre y el eje padre son obligatorios.");
    }

    try {
        $sql = "INSERT INTO componentes (eje_id, nombre, descripcion) VALUES (:eje_id, :nombre, :descripcion)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':eje_id' => $eje_id,
            ':nombre' => $nombre, 
            ':descripcion' => $descripcion
        ]);
        
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        die("Error al guardar el componente: " . $e->getMessage());
    }
} else {
    header('Location: index.php');
    exit;
}
?>