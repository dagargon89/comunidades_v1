<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Si no es POST, redirigir
    header('Location: registro.php');
    exit;
}

// 1. Recoger y limpiar datos
$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';

// 2. Validaciones
if (empty($nombre) || empty($email) || empty($password)) {
    header('Location: registro.php?error=Todos los campos son obligatorios');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: registro.php?error=El formato del correo no es válido');
    exit;
}

if ($password !== $password_confirm) {
    header('Location: registro.php?error=Las contraseñas no coinciden');
    exit;
}

// 3. Verificar si el email ya existe
try {
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        header('Location: registro.php?error=El correo electrónico ya está registrado');
        exit;
    }
} catch (PDOException $e) {
    die("Error en la base de datos: " . $e->getMessage());
}


// 4. Hashear la contraseña (¡NUNCA GUARDAR CONTRASEÑAS EN TEXTO PLANO!)
$password_hashed = password_hash($password, PASSWORD_DEFAULT);

// 5. Insertar el nuevo usuario en la base de datos
try {
    $sql = "INSERT INTO usuarios (nombre, email, password) VALUES (:nombre, :email, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nombre' => $nombre,
        ':email' => $email,
        ':password' => $password_hashed
    ]);

    // Redirigir al login con un mensaje de éxito
    header('Location: login.php?success=¡Registro completado! Ahora puedes iniciar sesión.');
    exit;

} catch (PDOException $e) {
    die("Error al registrar el usuario: " . $e->getMessage());
}
?>