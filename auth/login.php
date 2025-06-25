<?php
require_once '../includes/config.php';
// Si el usuario ya está logueado, redirigir al dashboard
if (isAuthenticated()) {
    header('Location: /index.php');
    exit;
}
$page_title = 'Inicio de Sesión';
include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="card shadow-lg border-0 rounded-lg mt-5">
            <div class="card-header">
                <h3 class="text-center font-weight-light my-4">Iniciar Sesión</h3>
            </div>
            <div class="card-body">
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
                <?php endif; ?>
                 <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
                <?php endif; ?>
                <form action="proceso_login.php" method="post">
                    <div class="form-floating mb-3">
                        <input class="form-control" id="email" name="email" type="email" placeholder="nombre@ejemplo.com" required />
                        <label for="email">Correo Electrónico</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input class="form-control" id="password" name="password" type="password" placeholder="Contraseña" required />
                        <label for="password">Contraseña</label>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                        <a class="small" href="#">¿Olvidaste tu contraseña?</a>
                        <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center py-3">
                <div class="small"><a href="registro.php">¿No tienes una cuenta? ¡Regístrate!</a></div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>