<?php
require_once 'includes/config.php';
// Hechizo de protección: Solo usuarios autenticados pueden ver esta página.
requireAuth();

// --- LÓGICA DE NEGOCIO Y CONSULTAS A LA BD ---

// 1. Obtener métricas principales
$stats = [
    'ejes' => $pdo->query("SELECT COUNT(*) FROM ejes")->fetchColumn(),
    'componentes' => $pdo->query("SELECT COUNT(*) FROM componentes")->fetchColumn(),
    'productos' => $pdo->query("SELECT COUNT(*) FROM productos")->fetchColumn(),
    'actividades' => $pdo->query("SELECT COUNT(*) FROM actividades")->fetchColumn(),
    'beneficiarios' => $pdo->query("SELECT COUNT(*) FROM beneficiarios")->fetchColumn(),
];

// 2. Datos para la gráfica "Productos por Eje"
$productosPorEjeData = $pdo->query("
    SELECT e.nombre, COUNT(p.id) as total
    FROM ejes e
    LEFT JOIN componentes c ON e.id = c.eje_id
    LEFT JOIN productos p ON c.id = p.componente_id
    GROUP BY e.id, e.nombre
    ORDER BY e.nombre
")->fetchAll();

// 3. Datos para la gráfica "Actividades por Estatus"
$actividadesPorEstatusData = $pdo->query("
    SELECT estatus, COUNT(*) as total
    FROM actividades
    GROUP BY estatus
")->fetchAll();

// 4. Obtener próximas actividades
$proximas_actividades = $pdo->query("
    SELECT a.nombre, a.fecha_inicio, p.nombre as producto_nombre
    FROM actividades a
    JOIN productos p ON a.producto_id = p.id
    WHERE a.estatus = 'Programada' AND a.fecha_inicio >= CURDATE()
    ORDER BY a.fecha_inicio ASC
    LIMIT 5
")->fetchAll();


// --- PREPARAR DATOS PARA LAS GRÁFICAS ---

// Datos para Gráfica 1
$labelsProductosPorEje = [];
$dataProductosPorEje = [];
foreach ($productosPorEjeData as $item) {
    $labelsProductosPorEje[] = $item['nombre'];
    $dataProductosPorEje[] = $item['total'];
}

// Datos para Gráfica 2
$labelsActividadesPorEstatus = [];
$dataActividadesPorEstatus = [];
foreach ($actividadesPorEstatusData as $item) {
    $labelsActividadesPorEstatus[] = $item['estatus'];
    $dataActividadesPorEstatus[] = $item['total'];
}

$page_title = 'Dashboard';
include 'includes/header.php';
?>

<h1 class="h2 mb-4">Dashboard</h1>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Ejes Estratégicos</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['ejes'] ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-bullseye fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Productos</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['productos'] ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box-open fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Actividades</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['actividades'] ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tasks fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Beneficiarios</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stats['beneficiarios'] ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-7 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Productos por Eje Estratégico</h6>
            </div>
            <div class="card-body">
                <canvas id="productosPorEjeChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-5 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Actividades por Estatus</h6>
            </div>
            <div class="card-body">
                <canvas id="actividadesPorEstatusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Gráfica 1: Productos por Eje
    const ctxProductos = document.getElementById('productosPorEjeChart').getContext('2d');
    new Chart(ctxProductos, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labelsProductosPorEje) ?>,
            datasets: [{
                label: 'Nº de Productos',
                data: <?= json_encode($dataProductosPorEje) ?>,
                backgroundColor: 'rgba(78, 115, 223, 0.7)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Gráfica 2: Actividades por Estatus
    const ctxActividades = document.getElementById('actividadesPorEstatusChart').getContext('2d');
    new Chart(ctxActividades, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($labelsActividadesPorEstatus) ?>,
            datasets: [{
                label: 'Nº de Actividades',
                data: <?= json_encode($dataActividadesPorEstatus) ?>,
                backgroundColor: [
                    'rgba(28, 200, 138, 0.7)',
                    'rgba(246, 194, 62, 0.7)',
                    'rgba(231, 74, 59, 0.7)'
                ],
            }]
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>