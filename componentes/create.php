<?php
require_once '../includes/config.php';
// requireAuth();

// Necesitamos obtener todos los ejes para el menú desplegable
try {
    $ejes = $pdo->query("SELECT id, nombre FROM ejes ORDER BY nombre")->fetchAll();
} catch (PDOException $e) {
    die("Error al cargar los ejes: " . $e->getMessage());
}

$page_title = 'Nuevo Componente';
include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="h5 mb-0">Nuevo Componente</h3>
            </div>
            <div class="card-body">
                <form action="store.php" method="POST">
                    <div class="mb-3">
                        <label for="eje_id" class="form-label">Eje Estratégico Padre</label>
                        <select class="form-select" name="eje_id" id="eje_id" required>
                            <option value="">-- Selecciona un Eje --</option>
                            <?php foreach ($ejes as $eje): ?>
                                <option value="<?= $eje['id'] ?>"><?= htmlspecialchars($eje['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Componente</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" required>
                    </div>
                     <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" id="descripcion" rows="4"></textarea>
                    </div>
                    <div class="text-end">
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar Componente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>