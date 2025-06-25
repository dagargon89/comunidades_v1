<?php
require_once '../includes/config.php';
// requireRole('admin'); // Protegeremos esta página en la siguiente fase

try {
    // Obtenemos todos los usuarios y sus roles concatenados
    $sql = "SELECT u.id, u.nombre, u.email, GROUP_CONCAT(r.nombre SEPARATOR ', ') as roles
            FROM usuarios u
            LEFT JOIN usuario_roles ur ON u.id = ur.usuario_id
            LEFT JOIN roles r ON ur.rol_id = r.id
            GROUP BY u.id
            ORDER BY u.nombre";
    $usuarios = $pdo->query($sql)->fetchAll();
} catch (PDOException $e) {
    die("Error al obtener usuarios: " . $e->getMessage());
}

$page_title = 'Gestionar Usuarios y Roles';
include '../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Gestionar Usuarios y Roles</h1>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover">
            <thead class="table-light">
                <tr><th>Nombre</th><th>Email</th><th>Roles Asignados</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php foreach($usuarios as $usuario): ?>
                <tr>
                    <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                    <td><?= htmlspecialchars($usuario['email']) ?></td>
                    <td><?= htmlspecialchars($usuario['roles'] ?? 'Ninguno') ?></td>
                    <td>
                        <a href="editar_roles.php?usuario_id=<?= $usuario['id'] ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-user-shield me-1"></i> Gestionar Roles
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../includes/footer.php'; ?>