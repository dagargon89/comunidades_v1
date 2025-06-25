<?php
require_once '../includes/config.php';
// requireAuth();

try {
    $organizaciones = $pdo->query("SELECT * FROM organizaciones ORDER BY nombre")->fetchAll();
} catch (PDOException $e) {
    die("Error al obtener las organizaciones: " . $e->getMessage());
}

$page_title = 'Organizaciones';
include '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Organizaciones</h1>
    <a href="create.php" class="btn btn-primary">
        <i class="fas fa-plus mr-1"></i> Nueva Organización
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($organizaciones as $org): ?>
                <tr>
                    <td><?= $org['id'] ?></td>
                    <td><?= htmlspecialchars($org['nombre']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $org['id'] ?>" class="btn btn-sm btn-outline-primary me-2">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="delete.php?id=<?= $org['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro?')">
                            <i class="fas fa-trash"></i> Eliminar
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>