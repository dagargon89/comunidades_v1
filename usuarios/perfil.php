<?php
require_once '../includes/config.php';
requireAuth(); 

$user_id = $_SESSION['user_id'];

try {
    // Obtener datos del usuario
    $stmt_user = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt_user->execute([$user_id]);
    $usuario = $stmt_user->fetch();

    // Obtener todas las organizaciones para el desplegable
    $organizaciones = $pdo->query("SELECT id, nombre FROM organizaciones ORDER BY nombre")->fetchAll();

} catch (PDOException $e) {
    die("Error al obtener los datos: " . $e->getMessage());
}

$page_title = 'Mi Perfil';
include '../includes/header.php';
?>

<h1 class="h2 mb-4">Mi Perfil</h1>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_GET['success']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<?php if (isset($_GET['error'])): ?>
     <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_GET['error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>


<div class="row">
    <div class="col-lg-7 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header"><h3 class="h5 mb-0">Actualizar mis Datos</h3></div>
            <div class="card-body">
                <form action="update_perfil.php" method="POST">
                    <input type="hidden" name="action" value="update_profile">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="organizacion_id" class="form-label">Organización</label>
                            <select class="form-select" id="organizacion_id" name="organizacion_id">
                                <option value="">-- Ninguna --</option>
                                <?php foreach($organizaciones as $org): ?>
                                    <option value="<?= $org['id'] ?>" <?= ($org['id'] == $usuario['organizacion_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($org['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                            <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" value="<?= htmlspecialchars($usuario['apellido_paterno'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="apellido_materno" class="form-label">Apellido Materno</label>
                            <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" value="<?= htmlspecialchars($usuario['apellido_materno'] ?? '') ?>">
                        </div>
                         <div class="col-md-6">
                            <label for="puesto" class="form-label">Puesto</label>
                            <input type="text" class="form-control" id="puesto" name="puesto" value="<?= htmlspecialchars($usuario['puesto'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5 mb-4">
        <div class="card shadow-sm h-100">
             <div class="card-header"><h3 class="h5 mb-0">Cambiar Contraseña</h3></div>
             <div class="card-body d-flex flex-column">
                <form action="update_perfil.php" method="POST">
                    <input type="hidden" name="action" value="update_password">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Contraseña Actual</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_new_password" class="form-label">Confirmar Nueva Contraseña</label>
                        <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" required>
                    </div>
                    <div class="text-end mt-auto">
                        <button type="submit" class="btn btn-warning">Cambiar Contraseña</button>
                    </div>
                </form>
             </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>