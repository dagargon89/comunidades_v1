<?php
require_once '../includes/config.php';
// requireAuth();

$id = (int)($_GET['id'] ?? 0);
if ($id === 0) {
    header('Location: index.php');
    exit;
}

try {
    // Obtenemos el producto a editar
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->execute([$id]);
    $producto = $stmt->fetch();
    if (!$producto) {
        header('Location: index.php');
        exit;
    }
    
    // Obtenemos todos los componentes para el desplegable
    $sql_componentes = "SELECT c.id, c.nombre, e.nombre AS eje_nombre 
                        FROM componentes c
                        JOIN ejes e ON c.eje_id = e.id
                        ORDER BY e.nombre, c.nombre";
    $componentes = $pdo->query($sql_componentes)->fetchAll();

} catch (PDOException $e) {
    die("Error en la base de datos: " . $e->getMessage());
}

$page_title = 'Editar Producto';
include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
             <div class="card-header">
                <h3 class="h5 mb-0">Editar Producto</h3>
            </div>
            <div class="card-body">
                <form action="update.php" method="POST">
                    <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                    <div class="mb-3">
                        <label for="componente_id" class="form-label">Componente Padre</label>
                        <select class="form-select" name="componente_id" id="componente_id" required>
                            <option value="">-- Selecciona un Componente --</option>
                            <?php foreach ($componentes as $componente): ?>
                                <option value="<?= $componente['id'] ?>" <?= ($componente['id'] === $producto['componente_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($componente['eje_nombre']) ?> &gt; <?= htmlspecialchars($componente['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Producto</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" required value="<?= htmlspecialchars($producto['nombre']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="tipo_producto" class="form-label">Tipo de Producto</label>
                        <input type="text" class="form-control" name="tipo_producto" id="tipo_producto" value="<?= htmlspecialchars($producto['tipo_producto']) ?>" placeholder="Ej: Contenido, Proceso, Gestión...">
                    </div>
                     <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" id="descripcion" rows="4"><?= htmlspecialchars($producto['descripcion']) ?></textarea>
                    </div>
                    <div class="text-end">
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>