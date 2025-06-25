<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

// 1. Recoger datos
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header('Location: login.php?error=Correo y contraseña son obligatorios');
    exit;
}

// 2. Buscar al usuario por email
try {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND activo = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // 3. Verificar si el usuario existe y la contraseña es correcta
    if ($user && password_verify($password, $user['password'])) {
        // ¡Credenciales correctas!
        
        // 4. Regenerar ID de sesión por seguridad
        session_regenerate_id(true);

        // 5. Guardar datos del usuario en la sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nombre'] = $user['nombre'];
        $_SESSION['user_email'] = $user['email'];

        // --- BLOQUE ACTUALIZADO Y CRUCIAL ---
        // Cargar los roles del usuario en la sesión
        $stmt_roles = $pdo->prepare("
            SELECT r.nombre 
            FROM roles r 
            JOIN usuario_roles ur ON r.id = ur.rol_id 
            WHERE ur.usuario_id = ?
        ");
        $stmt_roles->execute([$user['id']]);
        $roles_usuario = $stmt_roles->fetchAll(PDO::FETCH_COLUMN);
        $_SESSION['user_roles'] = $roles_usuario; // Guardamos un array con los nombres de los roles
        // --- FIN DEL BLOQUE ACTUALIZADO ---

        // 6. Redirigir al dashboard principal
        header('Location: /index.php');
        exit;

    } else {
        // Credenciales incorrectas
        header('Location: login.php?error=Correo o contraseña incorrectos');
        exit;
    }

} catch (PDOException $e) {
    die("Error en la base de datos: " . $e->getMessage());
}
?>