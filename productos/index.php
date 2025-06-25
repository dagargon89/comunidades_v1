<?php
require_once '../includes/config.php';
// requireAuth(); 

// Consulta más compleja con doble JOIN para obtener toda la jerarquía
$sql = "SELECT p.id, p.nombre, p.tipo_producto, c.nombre AS componente_nombre, e.nombre AS eje_nombre
        FROM productos p
        JOIN componentes c ON p.componente_id = c.id
        JOIN ejes e ON c.eje_id = e.id
        ORDER BY e.id, c.id, p.id";

try {
    $productos = $pdo->query($sql)->fetchAll();
} catch (PDOException $e) {
    die("Error al obtener los productos: " . $e->getMessage());
}

$page_title = 'Productos';
include '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Productos</h1>
    <a href="create.php" class="btn btn-primary">
        <i class="fas fa-plus mr-1"></i> Nuevo Producto
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th scope="col">Producto</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Componente Padre</th>
                    <th scope="col">Eje Padre</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($productos)): ?>
                    <tr>
                        <td colspan="5" class="text-center">No hay productos registrados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?= htmlspecialchars($producto['nombre']) ?></td>
                        <td><?= htmlspecialchars($producto['tipo_producto']) ?></td>
                        <td><?= htmlspecialchars($producto['componente_nombre']) ?></td>
                        <td><?= htmlspecialchars($producto['eje_nombre']) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $producto['id'] ?>" class="btn btn-sm btn-outline-primary me-2" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="delete.php?id=<?= $producto['id'] ?>" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Estás seguro?')">
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