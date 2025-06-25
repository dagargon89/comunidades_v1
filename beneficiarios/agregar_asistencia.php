<?php
require_once '../includes/config.php';
// requireAuth();

$actividad_id = (int)($_GET['actividad_id'] ?? 0);
if ($actividad_id === 0) {
    header('Location: index.php');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT nombre FROM actividades WHERE id = ?");
    $stmt->execute([$actividad_id]);
    $actividad = $stmt->fetch();
    if (!$actividad) {
        header('Location: index.php');
        exit;
    }
} catch(PDOException $e) {
    die("Error en la base de datos: " . $e->getMessage());
}

$page_title = 'Registrar Asistencia';
include '../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Registrar Asistencia a: "<?= htmlspecialchars($actividad['nombre']) ?>"</h1>
    <a href="index.php?actividad_id=<?= $actividad_id ?>" class="btn btn-secondary">Volver</a>
</div>

<form action="guardar_asistencia.php" method="POST">
    <input type="hidden" name="actividad_id" value="<?= $actividad_id ?>">
    
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="tabla-asistencia">
                    <thead>
                        <tr>
                            <th>CURP (Opcional)</th>
                            <th>Nombre(s)</th>
                            <th>Apellido Paterno</th>
                            <th>Fecha Asistencia</th>
                            <th>Observaciones</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="asistencia-body">
                        </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-between">
        <button type="button" onclick="agregarFila()" class="btn btn-success">
            <i class="fas fa-plus me-1"></i> Agregar Beneficiario
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Guardar Todo
        </button>
    </div>
</form>

<script>
let filaIndex = 0;

function agregarFila() {
    const tbody = document.getElementById('asistencia-body');
    const index = filaIndex++;
    const fila = document.createElement('tr');
    
    const hoy = new Date().toISOString().slice(0, 10);

    fila.innerHTML = `
        <td><input type="text" name="beneficiarios[${index}][curp]" class="form-control curp-input" data-index="${index}" style="text-transform:uppercase"></td>
        <td><input type="text" name="beneficiarios[${index}][nombre]" id="nombre_${index}" class="form-control" required></td>
        <td><input type="text" name="beneficiarios[${index}][apellido_paterno]" id="apellido_paterno_${index}" class="form-control" required></td>
        <td><input type="date" name="beneficiarios[${index}][fecha_asistencia]" class="form-control" value="${hoy}" required></td>
        <td><input type="text" name="beneficiarios[${index}][observaciones]" class="form-control"></td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">X</button></td>
    `;
    tbody.appendChild(fila);
}

document.addEventListener('change', async function(e) {
    if (e.target && e.target.classList.contains('curp-input')) {
        const curp = e.target.value;
        const index = e.target.dataset.index;
        if (curp.length >= 10) { // Longitud mínima para buscar
            try {
                const response = await fetch('buscar_por_curp.php?curp=' + encodeURIComponent(curp));
                const data = await response.json();
                if (data.id) {
                    document.getElementById(`nombre_${index}`).value = data.nombre;
                    document.getElementById(`apellido_paterno_${index}`).value = data.apellido_paterno;
                    // Deshabilitar campos para evitar edición accidental de existentes
                    document.getElementById(`nombre_${index}`).readOnly = true;
                    document.getElementById(`apellido_paterno_${index}`).readOnly = true;
                } else {
                     document.getElementById(`nombre_${index}`).readOnly = false;
                    document.getElementById(`apellido_paterno_${index}`).readOnly = false;
                }
            } catch (error) {
                console.error('Error al buscar beneficiario:', error);
            }
        }
    }
});

// Agregar la primera fila al cargar la página
document.addEventListener('DOMContentLoaded', agregarFila);
</script>

<?php include '../includes/footer.php'; ?>