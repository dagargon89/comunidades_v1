<?php
require_once '../../includes/config.php';
requireAuth();
requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    if (empty($nombre)) { die("El nombre es obligatorio."); }

    try {
        $sql = "INSERT INTO roles (nombre, descripcion) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $descripcion]);
        header('Location: index.php');
        exit;
    } catch (PDOException $e) { 
        if ($e->getCode() == 23000) {
             die("Error al guardar: El nombre del rol ya existe.");
        }
        die("Error al guardar: " . $e->getMessage()); 
    }
}
?>