<?php
require_once '../includes/config.php';
$page_title = 'Nueva Organización';
include '../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header"><h3 class="h5 mb-0">Nueva Organización</h3></div>
            <div class="card-body">
                <form action="store.php" method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Organización</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" required>
                    </div>
                    <div class="text-end">
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar Organización</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>