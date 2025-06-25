<?php
require_once '../includes/config.php';
// requireAuth();

$id = (int)($_GET['id'] ?? 0);
if ($id === 0) {
    header('Location: index.php');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM beneficiarios WHERE id = ?");
    $stmt->execute([$id]);
    $b = $stmt->fetch();
    if (!$b) {
        header('Location: index.php');
        exit;
    }
} catch (PDOException $e) {
    die("Error en la base de datos: " . $e->getMessage());
}

$page_title = 'Editar Beneficiario';
include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm">
            <div class="card-header"><h3 class="h5 mb-0">Editar Beneficiario</h3></div>
            <div class="card-body">
                <form action="update.php" method="POST">
                    <input type="hidden" name="id" value="<?= $b['id'] ?>">
                    <h4 class="h6 mb-3">Información Personal</h4>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="nombre" class="form-label">Nombre(s)</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($b['nombre']) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                            <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" value="<?= htmlspecialchars($b['apellido_paterno']) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="apellido_materno" class="form-label">Apellido Materno</label>
                            <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" value="<?= htmlspecialchars($b['apellido_materno']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= htmlspecialchars($b['fecha_nacimiento']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="sexo" class="form-label">Sexo</label>
                            <input type="text" class="form-control" id="sexo" name="sexo" value="<?= htmlspecialchars($b['sexo']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="curp" class="form-label">CURP</label>
                            <input type="text" class="form-control" id="curp" name="curp" style="text-transform:uppercase" value="<?= htmlspecialchars($b['curp']) ?>">
                        </div>
                    </div>

                    <hr class="my-4">
                    <h4 class="h6 mb-3">Información de Contacto y Demográfica</h4>
                     <div class="row g-3">
                         <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" value="<?= htmlspecialchars($b['telefono']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($b['email']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="escolaridad" class="form-label">Escolaridad</label>
                            <input type="text" class="form-control" id="escolaridad" name="escolaridad" value="<?= htmlspecialchars($b['escolaridad']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="ocupacion" class="form-label">Ocupación</label>
                            <input type="text" class="form-control" id="ocupacion" name="ocupacion" value="<?= htmlspecialchars($b['ocupacion']) ?>">
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    <h4 class="h6 mb-3">Dirección</h4>
                     <div class="row g-3">
                        <div class="col-12">
                            <label for="calle_numero" class="form-label">Calle y Número</label>
                            <input type="text" class="form-control" id="calle_numero" name="calle_numero" value="<?= htmlspecialchars($b['calle_numero']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="colonia" class="form-label">Colonia</label>
                            <input type="text" class="form-control" id="colonia" name="colonia" value="<?= htmlspecialchars($b['colonia']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="codigo_postal" class="form-label">Código Postal</label>
                            <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" value="<?= htmlspecialchars($b['codigo_postal']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="municipio" class="form-label">Municipio</label>
                            <input type="text" class="form-control" id="municipio" name="municipio" value="<?= htmlspecialchars($b['municipio']) ?>">
                        </div>
                     </div>
                     
                    <div class="text-end mt-4">
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar Beneficiario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>