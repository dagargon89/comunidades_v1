<?php
require_once '../includes/config.php';
// requireAuth();

// --- Lógica para obtener datos ---
$actividad_seleccionada = null;
$beneficiarios = [];
$actividad_id = (int)($_GET['actividad_id'] ?? 0);

try {
    // Siempre obtenemos la lista de actividades para el desplegable
    $actividades_disponibles = $pdo->query("SELECT id, nombre, fecha_inicio FROM actividades ORDER BY fecha_inicio DESC")->fetchAll();

    if ($actividad_id > 0) {
        // Si se seleccionó una actividad, filtramos los beneficiarios
        $stmt = $pdo->prepare("
            SELECT b.*, ab.fecha_asistencia, ab.observaciones 
            FROM beneficiarios b
            JOIN actividad_beneficiario ab ON b.id = ab.beneficiario_id
            WHERE ab.actividad_id = ?
            ORDER BY b.apellido_paterno, b.nombre
        ");
        $stmt->execute([$actividad_id]);
        $beneficiarios = $stmt->fetchAll();
        
        // Obtenemos los datos de la actividad seleccionada para mostrar su nombre
        $stmt_actividad = $pdo->prepare("SELECT * FROM actividades WHERE id = ?");
        $stmt_actividad->execute([$actividad_id]);
        $actividad_seleccionada = $stmt_actividad->fetch();

    } else {
        // Si no, mostramos a todos los beneficiarios del sistema
        $beneficiarios = $pdo->query("SELECT * FROM beneficiarios ORDER BY apellido_paterno, nombre")->fetchAll();
    }
} catch (PDOException $e) {
    die("Error al obtener datos: " . $e->getMessage());
}

$page_title = 'Beneficiarios';
include '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Gestión de Beneficiarios</h1>
    <div>
        <?php if ($actividad_seleccionada): ?>
             <a href="agregar_asistencia.php?actividad_id=<?= $actividad_id ?>" class="btn btn-success">
                <i class="fas fa-user-plus me-1"></i> Registrar Asistencia
            </a>
        <?php endif; ?>
        <a href="create.php" class="btn btn-primary">
            <i class="fas fa-user-plus me-1"></i> Nuevo Beneficiario
        </a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="index.php" method="GET" class="row g-3 align-items-end">
            <div class="col-md-10">
                <label for="actividad_id" class="form-label">Filtrar beneficiarios por actividad</label>
                <select name="actividad_id" id="actividad_id" class="form-select">
                    <option value="0">-- Mostrar todos los beneficiarios --</option>
                    <?php foreach ($actividades_disponibles as $act): ?>
                        <option value="<?= $act['id'] ?>" <?= ($actividad_id === $act['id']) ? 'selected' : '' ?>>
                            <?= date('d/m/Y', strtotime($act['fecha_inicio'])) ?> - <?= htmlspecialchars($act['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-info w-100">Filtrar</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="h5 mb-0">
            <?php if ($actividad_seleccionada): ?>
                Beneficiarios de la Actividad: "<?= htmlspecialchars($actividad_seleccionada['nombre']) ?>"
            <?php else: ?>
                Listado General de Beneficiarios
            <?php endif; ?>
        </h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Nombre Completo</th>
                        <th scope="col">CURP</th>
                        <?php if ($actividad_seleccionada): ?>
                            <th scope="col">Fecha Asistencia</th>
                            <th scope="col">Observaciones</th>
                        <?php else: ?>
                            <th scope="col">Teléfono</th>
                        <?php endif; ?>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($beneficiarios)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <p>No hay beneficiarios para mostrar.</p>
                                <?php if($actividad_seleccionada): ?>
                                    <p>Puedes registrar la asistencia para esta actividad.</p>
                                    <a href="agregar_asistencia.php?actividad_id=<?= $actividad_id ?>" class="btn btn-success mt-2">
                                        <i class="fas fa-user-plus me-1"></i> Registrar Asistencia Ahora
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($beneficiarios as $b): ?>
                        <tr>
                            <td><?= htmlspecialchars($b['nombre'] . ' ' . $b['apellido_paterno'] . ' ' . $b['apellido_materno']) ?></td>
                            <td><?= htmlspecialchars($b['curp']) ?></td>
                            <?php if ($actividad_seleccionada): ?>
                                <td><?= date('d/m/Y', strtotime($b['fecha_asistencia'])) ?></td>
                                <td><?= htmlspecialchars($b['observaciones']) ?></td>
                            <?php else: ?>
                                <td><?= htmlspecialchars($b['telefono']) ?></td>
                            <?php endif; ?>
                            <td>
                                <a href="edit.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-outline-primary" title="Editar Beneficiario"><i class="fas fa-edit"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>