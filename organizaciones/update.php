<?php
require_once '../includes/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    if ($id === 0 || empty($nombre)) { die("Datos inválidos."); }
    try {
        $sql = "UPDATE organizaciones SET nombre = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $id]);
        header('Location: index.php');
        exit;
    } catch (PDOException $e) { die("Error al actualizar: " . $e->getMessage()); }
}
?>