<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'comunidades_db'); // Base de datos del nuevo proyecto
define('DB_USER', 'root');
define('DB_PASS', 'Comunica25!');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Configuración de la zona horaria
date_default_timezone_set('America/Mexico_City');

// Configuración de sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Función para limpiar datos de entrada
function clean($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Función para verificar si el usuario está autenticado
function isAuthenticated() {
    return isset($_SESSION['user_id']);
}

// Función para redirigir si no está autenticado
function requireAuth() {
    if (!isAuthenticated()) {
        header('Location: /auth/login.php'); // Asegúrate que la ruta sea correcta
        exit();
    }
}

/**
 * Verifica si el usuario logueado tiene un rol específico.
 * @param string $rol El nombre del rol a verificar (ej. 'admin')
 * @return bool
 */
function hasRole(string $rol): bool {
    if (!isAuthenticated()) {
        return false;
    }
    // Comprueba si el array de roles existe en la sesión y si el rol está en el array
    return in_array($rol, $_SESSION['user_roles'] ?? []);
}

/**
 * Exige que el usuario tenga un rol específico para acceder a la página.
 * Si no lo tiene, muestra un error y detiene la ejecución.
 * @param string $rol El nombre del rol requerido.
 */
function requireRole(string $rol) {
    if (!hasRole($rol)) {
        http_response_code(403);
        // Puedes crear una página de error más elegante si lo deseas
        die('Acceso Prohibido. No tienes los permisos necesarios para ver esta página.');
    }
}

// Configuración para mostrar errores durante el desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);