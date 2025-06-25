<?php
require_once '../../includes/config.php';
requireAuth();
requireRole('admin');

$page_title = 'Nuevo Rol';
include '../../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header"><h3 class="h5 mb-0">Crear Nuevo Rol</h3></div>
            <div class="card-body">
                <form action="store.php" method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Rol</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ej: gestor" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" id="descripcion" rows="3" placeholder="Ej: Puede editar actividades y productos."></textarea>
                    </div>
                    <div class="text-end">
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar Rol</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include '../../includes/footer.php'; ?>