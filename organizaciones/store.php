<?php
require_once '../includes/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    if (empty($nombre)) { die("El nombre es obligatorio."); }
    try {
        $sql = "INSERT INTO organizaciones (nombre) VALUES (?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre]);
        header('Location: index.php');
        exit;
    } catch (PDOException $e) { die("Error al guardar: " . $e->getMessage()); }
}
?>