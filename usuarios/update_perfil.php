<?php
require_once '../includes/config.php';
requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: perfil.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

// --- LÓGICA PARA ACTUALIZAR DATOS DEL PERFIL ---
if ($action === 'update_profile') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $apellido_paterno = trim($_POST['apellido_paterno'] ?? '');
    $apellido_materno = trim($_POST['apellido_materno'] ?? '');
    $puesto = trim($_POST['puesto'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');

    if (empty($nombre) || empty($email)) {
        header('Location: perfil.php?error=El nombre y el email son obligatorios.');
        exit;
    }

    try {
        // Verificar que el nuevo email no esté ya en uso por otro usuario
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user_id]);
        if ($stmt->fetch()) {
            header('Location: perfil.php?error=El email ya está en uso por otro usuario.');
            exit;
        }

        // Actualizar los datos
        $sql = "UPDATE usuarios SET nombre = ?, email = ?, apellido_paterno = ?, apellido_materno = ?, puesto = ?, telefono = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $email, $apellido_paterno, $apellido_materno, $puesto, $telefono, $user_id]);

        // Actualizar los datos de la sesión
        $_SESSION['user_nombre'] = $nombre;
        $_SESSION['user_email'] = $email;
        
        header('Location: perfil.php?success=Perfil actualizado exitosamente.');
        exit;

    } catch (PDOException $e) {
        header('Location: perfil.php?error=' . urlencode('Error al actualizar el perfil: ' . $e->getMessage()));
        exit;
    }
}

// --- LÓGICA PARA CAMBIAR LA CONTRASEÑA ---
if ($action === 'update_password') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_new_password = $_POST['confirm_new_password'] ?? '';

    if (empty($current_password) || empty($new_password) || empty($confirm_new_password)) {
        header('Location: perfil.php?error=Todos los campos de contraseña son obligatorios.');
        exit;
    }

    if ($new_password !== $confirm_new_password) {
        header('Location: perfil.php?error=La nueva contraseña y su confirmación no coinciden.');
        exit;
    }

    try {
        // Obtener la contraseña actual hasheada del usuario
        $stmt = $pdo->prepare("SELECT password FROM usuarios WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        // Verificar que la contraseña actual es correcta
        if (!$user || !password_verify($current_password, $user['password'])) {
            header('Location: perfil.php?error=La contraseña actual es incorrecta.');
            exit;
        }

        // Hashear la nueva contraseña y actualizarla
        $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt_update = $pdo->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
        $stmt_update->execute([$new_password_hashed, $user_id]);
        
        header('Location: perfil.php?success=Contraseña cambiada exitosamente.');
        exit;

    } catch (PDOException $e) {
        header('Location: perfil.php?error=' . urlencode('Error al cambiar la contraseña: ' . $e->getMessage()));
        exit;
    }
}

// Si la acción no es reconocida, redirigir.
header('Location: perfil.php');
exit;