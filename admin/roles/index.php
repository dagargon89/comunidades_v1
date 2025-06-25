<?php
require_once '../../includes/config.php'; // Subimos dos niveles para llegar a includes
requireAuth();
requireRole('admin'); // Solo los administradores pueden gestionar roles

try {
    $roles = $pdo->query("SELECT * FROM roles ORDER BY nombre")->fetchAll();
} catch (PDOException $e) {
    die("Error al obtener los roles: " . $e->getMessage());
}

$page_title = 'Gestionar Roles';
include '../../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Gestionar Roles del Sistema</h1>
    <a href="create.php" class="btn btn-primary">
        <i class="fas fa-plus mr-1"></i> Nuevo Rol
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre del Rol</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($roles as $rol): ?>
                <tr>
                    <td><?= $rol['id'] ?></td>
                    <td><?= htmlspecialchars($rol['nombre']) ?></td>
                    <td><?= htmlspecialchars($rol['descripcion']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $rol['id'] ?>" class="btn btn-sm btn-outline-primary me-2">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="delete.php?id=<?= $rol['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro?')">
                            <i class="fas fa-trash"></i> Eliminar
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>