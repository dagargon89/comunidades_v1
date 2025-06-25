<?php
require_once '../includes/config.php';
$id = (int)($_GET['id'] ?? 0);

if ($id === 0) {
    header('Location: index.php');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM ejes WHERE id = ?");
    $stmt->execute([$id]);
    $eje = $stmt->fetch();
    if (!$eje) {
        header('Location: index.php');
        exit;
    }
} catch (PDOException $e) {
    die("Error al obtener el eje: " . $e->getMessage());
}

$page_title = 'Editar Eje Estratégico';
include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
             <div class="card-header">
                <h3 class="h5 mb-0">Editar Eje Estratégico</h3>
            </div>
            <div class="card-body">
                <form action="update.php" method="POST">
                    <input type="hidden" name="id" value="<?= $eje['id'] ?>">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Eje</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" required value="<?= htmlspecialchars($eje['nombre']) ?>">
                    </div>
                     <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" id="descripcion" rows="4"><?= htmlspecialchars($eje['descripcion']) ?></textarea>
                    </div>
                    <div class="text-end">
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar Eje</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>