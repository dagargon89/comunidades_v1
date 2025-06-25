<?php
require_once '../includes/config.php';
// requireAuth(); 

// Modificamos la consulta para unir las tablas y obtener el nombre del eje
$sql = "SELECT c.id, c.nombre, c.descripcion, e.nombre AS eje_nombre 
        FROM componentes c 
        JOIN ejes e ON c.eje_id = e.id 
        ORDER BY e.id, c.id";

try {
    $componentes = $pdo->query($sql)->fetchAll();
} catch (PDOException $e) {
    die("Error al obtener los componentes: " . $e->getMessage());
}

$page_title = 'Componentes';
include '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Componentes</h1>
    <a href="create.php" class="btn btn-primary">
        <i class="fas fa-plus mr-1"></i> Nuevo Componente
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre del Componente</th>
                    <th scope="col">Eje Estratégico Padre</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($componentes)): ?>
                    <tr>
                        <td colspan="4" class="text-center">No hay componentes registrados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($componentes as $componente): ?>
                    <tr>
                        <td><?= $componente['id'] ?></td>
                        <td><?= htmlspecialchars($componente['nombre']) ?></td>
                        <td><?= htmlspecialchars($componente['eje_nombre']) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $componente['id'] ?>" class="btn btn-sm btn-outline-primary me-2">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="delete.php?id=<?= $componente['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro?')">
                                <i class="fas fa-trash"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>