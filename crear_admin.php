<?php
// --- CONFIGURACIÓN Y CONEXIÓN ---
// Incluimos el corazón de la aplicación para tener acceso a la base de datos.
require_once 'includes/config.php';

echo "<pre>"; // Etiqueta para mejorar la legibilidad de la salida.

// --- DATOS DEL ADMINISTRADOR A CREAR ---
// !!! Modifica estos valores con los que desees para tu cuenta !!!
$nombre = "David García"; // Nombre del administrador.
$email = "dgarcia@planjuarez.org";
$password_plano = "Gagd891220"; // Una contraseña segura para tu admin.

// --- INICIO DEL PROCESO DE CREACIÓN ---

try {
    // 1. Hashear la contraseña. ¡La seguridad es primero!
    $password_hashed = password_hash($password_plano, PASSWORD_DEFAULT);
    echo "Contraseña hasheada exitosamente.\n";

    // 2. Iniciar una transacción para asegurar que todas las operaciones se completen o ninguna.
    $pdo->beginTransaction();
    echo "Iniciando transacción...\n";

    // 3. Verificar si el usuario o el rol de admin ya existen.
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        throw new Exception("El correo electrónico '{$email}' ya está registrado. No se creó el usuario.");
    }
    
    // 4. Insertar el nuevo usuario en la tabla 'usuarios'.
    $sql_usuario = "INSERT INTO usuarios (nombre, email, password, puesto) VALUES (:nombre, :email, :password, :puesto)";
    $stmt_usuario = $pdo->prepare($sql_usuario);
    $stmt_usuario->execute([
        ':nombre' => $nombre,
        ':email' => $email,
        ':password' => $password_hashed,
        ':puesto' => 'Administrador del Sistema'
    ]);
    $nuevo_usuario_id = $pdo->lastInsertId();
    echo "Usuario '{$nombre}' insertado en la tabla 'usuarios' con ID: {$nuevo_usuario_id}\n";

    // 5. Buscar el ID del rol 'admin'. Si no existe, lo creamos.
    $stmt_rol = $pdo->prepare("SELECT id FROM roles WHERE nombre = 'admin'");
    $stmt_rol->execute();
    $rol = $stmt_rol->fetch();
    
    if ($rol) {
        $rol_admin_id = $rol['id'];
        echo "Rol 'admin' encontrado con ID: {$rol_admin_id}\n";
    } else {
        echo "Rol 'admin' no encontrado. Creándolo...\n";
        $pdo->query("INSERT INTO roles (nombre, descripcion) VALUES ('admin', 'Acceso total al sistema')");
        $rol_admin_id = $pdo->lastInsertId();
        echo "Rol 'admin' creado con ID: {$rol_admin_id}\n";
    }

    // 6. Vincular el nuevo usuario con el rol de administrador.
    $sql_vincular = "INSERT INTO usuario_roles (usuario_id, rol_id) VALUES (?, ?)";
    $stmt_vincular = $pdo->prepare($sql_vincular);
    $stmt_vincular->execute([$nuevo_usuario_id, $rol_admin_id]);
    echo "Usuario ID {$nuevo_usuario_id} vinculado con Rol ID {$rol_admin_id} exitosamente.\n";

    // 7. Si todo salió bien, confirmamos la transacción.
    $pdo->commit();
    echo "\n¡TRANSACCIÓN COMPLETADA!\n";
    echo "=================================================\n";
    echo " USUARIO ADMINISTRADOR CREADO EXITOSAMENTE \n";
    echo "=================================================\n";

} catch (Exception $e) {
    // Si algo falló, revertimos todos los cambios.
    $pdo->rollBack();
    echo "\n¡ERROR! Se revirtieron todos los cambios.\n";
    echo "Detalle del error: " . $e->getMessage() . "\n";
}

echo "</pre>";

?>