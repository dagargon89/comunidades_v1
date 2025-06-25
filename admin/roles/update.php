<?php
require_once '../../includes/config.php';
requireAuth();
requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    if ($id === 0 || empty($nombre)) { die("Datos inválidos."); }

    try {
        $sql = "UPDATE roles SET nombre = ?, descripcion = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $descripcion, $id]);
        header('Location: index.php');
        exit;
    } catch (PDOException $e) { 
        if ($e->getCode() == 23000) {
             die("Error al actualizar: El nombre del rol ya existe.");
        }
        die("Error al actualizar: " . $e->getMessage()); 
    }
}
?>