<?php
require_once 'includes/config.php';
// requireAuth();

try {
    // Usamos UNION ALL para obtener Productos y Actividades en una sola consulta
    // Es importante darles un prefijo a los IDs para que no haya colisiones (ej. p_1, a_1)
    $sql = "
        -- Seleccionar todos los Productos
        SELECT 
            CONCAT('p_', id) AS id,
            nombre AS text,
            (SELECT MIN(fecha_inicio) FROM actividades WHERE producto_id = p.id) AS start_date,
            (SELECT MAX(fecha_fin) FROM actividades WHERE producto_id = p.id) AS end_date,
            'producto' AS tipo,
            NULL AS parent,
            NULL AS estatus
        FROM productos p

        UNION ALL

        -- Seleccionar todas las Actividades
        SELECT 
            CONCAT('a_', id) AS id,
            nombre AS text,
            fecha_inicio AS start_date,
            fecha_fin AS end_date,
            'actividad' AS tipo,
            CONCAT('p_', producto_id) AS parent,
            estatus
        FROM actividades
    ";
    $tasks_data = $pdo->query($sql)->fetchAll();

} catch (PDOException $e) {
    die("Error al obtener los datos para el Gantt: " . $e->getMessage());
}

$page_title = 'Diagrama de Gantt';
include 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2">Diagrama de Gantt</h1>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div id="gantt_here" style='width:100%; height:500px;'></div>
    </div>
</div>

<style>
    /* Estilo para las barras de los Productos (proyectos) */
    .gantt_task_line.gantt_producto .gantt_task_content {
        background-color: #0d6efd; 
    }
    .gantt_task_line.gantt_producto {
        border: 1px solid #0a58ca;
    }
    /* Estilo para las barras de las Actividades */
    .gantt_task_line.gantt_actividad_Programada .gantt_task_content {
        background-color: #ffc107;
    }
    .gantt_task_line.gantt_actividad_Realizada .gantt_task_content {
        background-color: #198754;
    }
     .gantt_task_line.gantt_actividad_Cancelada .gantt_task_content {
        background-color: #dc3545;
    }
</style>

<script>
    // Asignamos una clase CSS a cada tarea (barra) según su tipo y estatus
    gantt.templates.task_class = function(start, end, task){
        if (task.tipo === 'producto'){
            return "gantt_producto";
        } else {
            return "gantt_actividad_" + task.estatus;
        }
    };

    // Formato de la fecha en la escala de tiempo
    gantt.config.date_format = "%Y-%m-%d %H:%i:%s";
    gantt.config.scale_unit = "day";
    gantt.config.date_scale = "%d %M";
    
    // El diagrama será de solo lectura
    gantt.config.readonly = true;

    // Inicializamos el Gantt
    gantt.init("gantt_here");

    // Preparamos los datos para que el Gantt los entienda
    const tasks = {
        data: [
            <?php foreach ($tasks_data as $task): ?>
                {
                    id: "<?= $task['id'] ?>",
                    text: "<?= htmlspecialchars($task['text'], ENT_QUOTES) ?>",
                    start_date: "<?= date('Y-m-d H:i:s', strtotime($task['start_date'])) ?>",
                    end_date: "<?= date('Y-m-d H:i:s', strtotime($task['end_date'])) ?>",
                    parent: "<?= $task['parent'] ?>",
                    type: "<?= $task['tipo'] ?>",
                    estatus: "<?= $task['estatus'] ?>",
                    open: true // Mantenemos los productos abiertos por defecto
                },
            <?php endforeach; ?>
        ],
        links: [] // No tenemos dependencias entre tareas por ahora
    };

    // Cargamos los datos en el Gantt
    gantt.parse(tasks);
</script>

<?php include 'includes/footer.php'; ?>