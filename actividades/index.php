<?php
require_once '../includes/config.php';
// requireAuth(); 

$sql = "SELECT a.id, a.nombre, a.fecha_inicio, a.fecha_fin, a.estatus, p.nombre AS producto_nombre
        FROM actividades a
        JOIN productos p ON a.producto_id = p.id
        ORDER BY a.fecha_inicio DESC";

try {
    $actividades = $pdo->query($sql)->fetchAll();
} catch (PDOException $e) {
    die("Error al obtener las actividades: " . $e->getMessage());
}

$page_title = 'Actividades';
include '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Actividades</h1>
    <a href="create.php" class="btn btn-primary">
        <i class="fas fa-plus mr-1"></i> Nueva Actividad
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th scope="col">Actividad</th>
                    <th scope="col">Producto Padre</th>
                    <th scope="col">Fecha Inicio</th>
                    <th scope="col">Fecha Fin</th>
                    <th scope="col">Estatus</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($actividades)): ?>
                    <tr>
                        <td colspan="6" class="text-center">No hay actividades registradas.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($actividades as $actividad): ?>
                    <tr>
                        <td><?= htmlspecialchars($actividad['nombre']) ?></td>
                        <td><?= htmlspecialchars($actividad['producto_nombre']) ?></td>
                        <td><?= date('d/m/Y', strtotime($actividad['fecha_inicio'])) ?></td>
                        <td><?= date('d/m/Y', strtotime($actividad['fecha_fin'])) ?></td>
                        <td>
                            <span class="badge 
                                <?php 
                                    switch ($actividad['estatus']) {
                                        case 'Realizada': echo 'bg-success'; break;
                                        case 'Cancelada': echo 'bg-danger'; break;
                                        default: echo 'bg-warning text-dark'; break;
                                    }
                                ?>">
                                <?= htmlspecialchars($actividad['estatus']) ?>
                            </span>
                        </td>
                        <td>
                            <a href="edit.php?id=<?= $actividad['id'] ?>" class="btn btn-sm btn-outline-primary me-2" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="delete.php?id=<?= $actividad['id'] ?>" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Estás seguro?')">
                                <i class="fas fa-trash"></i>
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