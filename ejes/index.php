<?php
require_once '../includes/config.php';
// requireAuth();

try {
    $ejes = $pdo->query("SELECT * FROM ejes ORDER BY id")->fetchAll();
} catch (PDOException $e) {
    die("Error al obtener los ejes: " . $e->getMessage());
}

$page_title = 'Ejes Estratégicos';
include '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Ejes Estratégicos</h1>
    <a href="create.php" class="btn btn-primary">
        <i class="fas fa-plus mr-1"></i> Nuevo Eje
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
                <?php foreach ($ejes as $eje): ?>
                <tr>
                    <td><?= $eje['id'] ?></td>
                    <td><?= htmlspecialchars($eje['nombre']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $eje['id'] ?>" class="btn btn-sm btn-outline-primary me-2">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="delete.php?id=<?= $eje['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este eje?')">
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