<?php
require_once '../includes/config.php';
// requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = (int)($_POST['usuario_id'] ?? 0);
    $roles = $_POST['roles'] ?? []; // Array de IDs de roles seleccionados

    if ($usuario_id === 0) { header('Location: gestionar_usuarios.php'); exit; }

    try {
        $pdo->beginTransaction();

        // 1. Borrar todos los roles actuales del usuario
        $stmt_delete = $pdo->prepare("DELETE FROM usuario_roles WHERE usuario_id = ?");
        $stmt_delete->execute([$usuario_id]);

        // 2. Insertar los nuevos roles seleccionados
        if (!empty($roles)) {
            $stmt_insert = $pdo->prepare("INSERT INTO usuario_roles (usuario_id, rol_id) VALUES (?, ?)");
            foreach ($roles as $rol_id) {
                $stmt_insert->execute([$usuario_id, (int)$rol_id]);
            }
        }

        $pdo->commit();
        header('Location: gestionar_usuarios.php');
        exit;

    } catch(Exception $e) {
        $pdo->rollBack();
        die("Error al guardar los roles: " . $e->getMessage());
    }
}
?>