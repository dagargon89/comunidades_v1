<?php
require_once '../includes/config.php';
// requireRole('admin');

$usuario_id = (int)($_GET['usuario_id'] ?? 0);
if ($usuario_id === 0) { header('Location: gestionar_usuarios.php'); exit; }

try {
    // Datos del usuario
    $stmt_user = $pdo->prepare("SELECT id, nombre, email FROM usuarios WHERE id = ?");
    $stmt_user->execute([$usuario_id]);
    $usuario = $stmt_user->fetch();

    // Todos los roles disponibles
    $roles_disponibles = $pdo->query("SELECT id, nombre FROM roles")->fetchAll();

    // Roles que el usuario ya tiene
    $stmt_user_roles = $pdo->prepare("SELECT rol_id FROM usuario_roles WHERE usuario_id = ?");
    $stmt_user_roles->execute([$usuario_id]);
    $roles_actuales = $stmt_user_roles->fetchAll(PDO::FETCH_COLUMN);

} catch (PDOException $e) { die("Error: " . $e->getMessage()); }

$page_title = 'Editar Roles de Usuario';
include '../includes/header.php';
?>
<h1 class="h2 mb-4">Gestionar Roles para: <?= htmlspecialchars($usuario['nombre']) ?></h1>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="guardar_roles.php" method="POST">
            <input type="hidden" name="usuario_id" value="<?= $usuario_id ?>">
            <p>Selecciona los roles que deseas asignar a este usuario:</p>
            <?php foreach($roles_disponibles as $rol): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="roles[]" value="<?= $rol['id'] ?>" id="rol_<?= $rol['id'] ?>"
                        <?= in_array($rol['id'], $roles_actuales) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="rol_<?= $rol['id'] ?>">
                        <?= htmlspecialchars($rol['nombre']) ?>
                    </label>
                </div>
            <?php endforeach; ?>
            <div class="mt-4 text-end">
                <a href="gestionar_usuarios.php" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>
<?php include '../includes/footer.php'; ?>