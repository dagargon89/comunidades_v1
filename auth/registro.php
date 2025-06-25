<?php
require_once '../includes/config.php';
// Si el usuario ya está logueado, redirigir al dashboard
if (isAuthenticated()) {
    header('Location: /index.php');
    exit;
}
$page_title = 'Registro de Usuario';
include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="card shadow-lg border-0 rounded-lg mt-5">
            <div class="card-header">
                <h3 class="text-center font-weight-light my-4">Crear Cuenta</h3>
            </div>
            <div class="card-body">
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
                <?php endif; ?>
                <form action="proceso_registro.php" method="post">
                    <div class="form-floating mb-3">
                        <input class="form-control" id="nombre" name="nombre" type="text" placeholder="Juan Pérez" required />
                        <label for="nombre">Nombre Completo</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" id="email" name="email" type="email" placeholder="nombre@ejemplo.com" required />
                        <label for="email">Correo Electrónico</label>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3 mb-md-0">
                                <input class="form-control" id="password" name="password" type="password" placeholder="Crea una contraseña" required />
                                <label for="password">Contraseña</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3 mb-md-0">
                                <input class="form-control" id="password_confirm" name="password_confirm" type="password" placeholder="Confirma la contraseña" required />
                                <label for="password_confirm">Confirmar Contraseña</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 mb-0">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-block">Crear Cuenta</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center py-3">
                <div class="small"><a href="login.php">¿Ya tienes una cuenta? Inicia sesión</a></div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>