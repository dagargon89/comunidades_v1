<?php
// Lógica para detectar la página y directorio actual y así resaltar el enlace activo.
$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="/index.php">
            <i class="fas fa-project-diagram me-2"></i>
            Comunidades
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-navbar" aria-controls="main-navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="main-navbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'index.php') ? 'active' : '' ?>" href="/index.php">
                        <i class="fas fa-home me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_dir === 'organizaciones') ? 'active' : '' ?>" href="/organizaciones/index.php">
                        <i class="fas fa-building me-1"></i>Organizaciones
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($current_dir, ['ejes', 'componentes', 'productos']) ? 'active' : '' ?>" href="#" id="navbarDropdownEstructura" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-sitemap me-1"></i>Estructura
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownEstructura">
                        <li><a class="dropdown-item" href="/ejes/index.php">Ejes Estratégicos</a></li>
                        <li><a class="dropdown-item" href="/componentes/index.php">Componentes</a></li>
                        <li><a class="dropdown-item" href="/productos/index.php">Productos</a></li>
                    </ul>
                </li>
                 <li class="nav-item">
                    <a class="nav-link <?= ($current_dir === 'actividades') ? 'active' : '' ?>" href="/actividades/index.php">
                        <i class="fas fa-tasks me-1"></i>Actividades
                    </a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link <?= ($current_dir === 'beneficiarios') ? 'active' : '' ?>" href="/beneficiarios/index.php">
                        <i class="fas fa-users me-1"></i>Beneficiarios
                    </a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'gantt.php') ? 'active' : '' ?>" href="/gantt.php">
                        <i class="fas fa-chart-gantt me-1"></i>Gantt
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                
                <?php if (hasRole('admin')): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= ($current_dir === 'admin') ? 'active' : '' ?>" href="#" id="navbarDropdownAdmin" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-cogs me-1"></i>Administración
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownAdmin">
                            <li><a class="dropdown-item" href="/admin/roles/index.php">Gestionar Roles</a></li>
                            <li><a class="dropdown-item" href="/admin/gestionar_usuarios.php">Gestionar Usuarios</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownUsuario" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user me-1"></i><?= htmlspecialchars($_SESSION['user_nombre']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUsuario">
                            <li><a class="dropdown-item" href="/usuarios/perfil.php">Mi Perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/auth/logout.php">Cerrar Sesión</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/auth/login.php">Iniciar Sesión</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>