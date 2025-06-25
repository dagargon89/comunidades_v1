<?php
require_once '../includes/config.php';
$id = (int)($_GET['id'] ?? 0);
if ($id === 0) { header('Location: index.php'); exit; }
try {
    $stmt = $pdo->prepare("SELECT * FROM organizaciones WHERE id = ?");
    $stmt->execute([$id]);
    $org = $stmt->fetch();
    if (!$org) { header('Location: index.php'); exit; }
} catch (PDOException $e) { die("Error: " . $e->getMessage()); }
$page_title = 'Editar Organización';
include '../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header"><h3 class="h5 mb-0">Editar Organización</h3></div>
            <div class="card-body">
                <form action="update.php" method="POST">
                    <input type="hidden" name="id" value="<?= $org['id'] ?>">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Organización</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" value="<?= htmlspecialchars($org['nombre']) ?>" required>
                    </div>
                    <div class="text-end">
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar Organización</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>