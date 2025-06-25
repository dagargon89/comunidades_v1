<?php
require_once '../includes/config.php';
// requireAuth();

try {
    // Obtener productos para el desplegable
    $productos = $pdo->query("SELECT id, nombre FROM productos ORDER BY nombre")->fetchAll();
    // Obtener usuarios para el desplegable de responsables
    $usuarios = $pdo->query("SELECT id, nombre FROM usuarios WHERE activo = 1 ORDER BY nombre")->fetchAll();
} catch (PDOException $e) {
    die("Error al cargar datos para el formulario: " . $e->getMessage());
}

$page_title = 'Nueva Actividad';
include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header"><h3 class="h5 mb-0">Nueva Actividad</h3></div>
            <div class="card-body">
                <form action="store.php" method="POST">
                    <div class="mb-3">
                        <label for="producto_id" class="form-label">Producto Padre</label>
                        <select class="form-select" name="producto_id" id="producto_id" required>
                            <option value="">-- Selecciona un Producto --</option>
                            <?php foreach ($productos as $producto): ?>
                                <option value="<?= $producto['id'] ?>"><?= htmlspecialchars($producto['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Actividad</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                            <input type="datetime-local" class="form-control" name="fecha_inicio" id="fecha_inicio" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                            <input type="datetime-local" class="form-control" name="fecha_fin" id="fecha_fin" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="responsable_id" class="form-label">Responsable</label>
                        <select class="form-select" name="responsable_id" id="responsable_id">
                            <option value="">-- Sin asignar --</option>
                             <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?= $usuario['id'] ?>"><?= htmlspecialchars($usuario['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="estatus" class="form-label">Estatus</label>
                        <select class="form-select" name="estatus" id="estatus" required>
                            <option value="Programada">Programada</option>
                            <option value="Realizada">Realizada</option>
                            <option value="Cancelada">Cancelada</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="meta" class="form-label">Meta</label>
                        <textarea class="form-control" name="meta" id="meta" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="indicador" class="form-label">Indicador</label>
                        <textarea class="form-control" name="indicador" id="indicador" rows="2"></textarea>
                    </div>
                    <div class="text-end">
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar Actividad</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>