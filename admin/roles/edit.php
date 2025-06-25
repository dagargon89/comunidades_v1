<?php
require_once '../../includes/config.php';
requireAuth();
requireRole('admin');

$id = (int)($_GET['id'] ?? 0);
if ($id === 0) { header('Location: index.php'); exit; }

try {
    $stmt = $pdo->prepare("SELECT * FROM roles WHERE id = ?");
    $stmt->execute([$id]);
    $rol = $stmt->fetch();
    if (!$rol) { header('Location: index.php'); exit; }
} catch (PDOException $e) { die("Error: " . $e->getMessage()); }

$page_title = 'Editar Rol';
include '../../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header"><h3 class="h5 mb-0">Editar Rol</h3></div>
            <div class="card-body">
                <form action="update.php" method="POST">
                    <input type="hidden" name="id" value="<?= $rol['id'] ?>">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Rol</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" value="<?= htmlspecialchars($rol['nombre']) ?>" required>
                    </div>
                     <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" id="descripcion" rows="3"><?= htmlspecialchars($rol['descripcion']) ?></textarea>
                    </div>
                    <div class="text-end">
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar Rol</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>