<?php
require_once '../includes/config.php';
// requireAuth();

$id = (int)($_GET['id'] ?? 0);
if ($id === 0) {
    header('Location: index.php');
    exit;
}

try {
    // Obtener la actividad específica
    $stmt = $pdo->prepare("SELECT * FROM actividades WHERE id = ?");
    $stmt->execute([$id]);
    $actividad = $stmt->fetch();
    if (!$actividad) {
        header('Location: index.php');
        exit;
    }

    // Obtener productos y usuarios para los desplegables
    $productos = $pdo->query("SELECT id, nombre FROM productos ORDER BY nombre")->fetchAll();
    $usuarios = $pdo->query("SELECT id, nombre FROM usuarios WHERE activo = 1 ORDER BY nombre")->fetchAll();

} catch (PDOException $e) {
    die("Error en la base de datos: " . $e->getMessage());
}

$page_title = 'Editar Actividad';
include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header"><h3 class="h5 mb-0">Editar Actividad</h3></div>
            <div class="card-body">
                <form action="update.php" method="POST">
                    <input type="hidden" name="id" value="<?= $actividad['id'] ?>">
                    
                    <div class="mb-3">
                        <label for="producto_id" class="form-label">Producto Padre</label>
                        <select class="form-select" name="producto_id" id="producto_id" required>
                            <?php foreach ($productos as $producto): ?>
                                <option value="<?= $producto['id'] ?>" <?= ($producto['id'] === $actividad['producto_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($producto['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Actividad</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" value="<?= htmlspecialchars($actividad['nombre']) ?>" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                            <input type="datetime-local" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?= date('Y-m-d\TH:i', strtotime($actividad['fecha_inicio'])) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                            <input type="datetime-local" class="form-control" name="fecha_fin" id="fecha_fin" value="<?= date('Y-m-d\TH:i', strtotime($actividad['fecha_fin'])) ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="responsable_id" class="form-label">Responsable</label>
                        <select class="form-select" name="responsable_id" id="responsable_id">
                            <option value="">-- Sin asignar --</option>
                             <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?= $usuario['id'] ?>" <?= ($usuario['id'] === $actividad['responsable_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($usuario['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="estatus" class="form-label">Estatus</label>
                        <select class="form-select" name="estatus" id="estatus" required>
                            <option value="Programada" <?= ($actividad['estatus'] === 'Programada') ? 'selected' : '' ?>>Programada</option>
                            <option value="Realizada" <?= ($actividad['estatus'] === 'Realizada') ? 'selected' : '' ?>>Realizada</option>
                            <option value="Cancelada" <?= ($actividad['estatus'] === 'Cancelada') ? 'selected' : '' ?>>Cancelada</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="meta" class="form-label">Meta</label>
                        <textarea class="form-control" name="meta" id="meta" rows="2"><?= htmlspecialchars($actividad['meta']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="indicador" class="form-label">Indicador</label>
                        <textarea class="form-control" name="indicador" id="indicador" rows="2"><?= htmlspecialchars($actividad['indicador']) ?></textarea>
                    </div>
                    <div class="text-end">
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar Actividad</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>