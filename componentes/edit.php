<?php
require_once '../includes/config.php';
// requireAuth();

$id = (int)($_GET['id'] ?? 0);
if ($id === 0) {
    header('Location: index.php');
    exit;
}

try {
    // Obtenemos el componente a editar
    $stmt = $pdo->prepare("SELECT * FROM componentes WHERE id = ?");
    $stmt->execute([$id]);
    $componente = $stmt->fetch();
    if (!$componente) {
        header('Location: index.php');
        exit;
    }
    
    // Obtenemos todos los ejes para el desplegable
    $ejes = $pdo->query("SELECT id, nombre FROM ejes ORDER BY nombre")->fetchAll();

} catch (PDOException $e) {
    die("Error en la base de datos: " . $e->getMessage());
}

$page_title = 'Editar Componente';
include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
             <div class="card-header">
                <h3 class="h5 mb-0">Editar Componente</h3>
            </div>
            <div class="card-body">
                <form action="update.php" method="POST">
                    <input type="hidden" name="id" value="<?= $componente['id'] ?>">
                    <div class="mb-3">
                        <label for="eje_id" class="form-label">Eje Estratégico Padre</label>
                        <select class="form-select" name="eje_id" id="eje_id" required>
                            <option value="">-- Selecciona un Eje --</option>
                            <?php foreach ($ejes as $eje): ?>
                                <option value="<?= $eje['id'] ?>" <?= ($eje['id'] === $componente['eje_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($eje['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Componente</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" required value="<?= htmlspecialchars($componente['nombre']) ?>">
                    </div>
                     <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" id="descripcion" rows="4"><?= htmlspecialchars($componente['descripcion']) ?></textarea>
                    </div>
                    <div class="text-end">
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar Componente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>