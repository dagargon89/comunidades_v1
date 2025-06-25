<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$actividad_id = (int)($_POST['actividad_id'] ?? 0);
$beneficiarios_data = $_POST['beneficiarios'] ?? [];

if ($actividad_id === 0 || empty($beneficiarios_data)) {
    header('Location: index.php?actividad_id=' . $actividad_id . '&error=No se recibieron datos');
    exit;
}

try {
    $pdo->beginTransaction();

    foreach ($beneficiarios_data as $data) {
        $nombre = trim($data['nombre']);
        $apellido_paterno = trim($data['apellido_paterno']);
        $curp = !empty($data['curp']) ? strtoupper(trim($data['curp'])) : null;
        $fecha_asistencia = $data['fecha_asistencia'];
        $observaciones = trim($data['observaciones']);
        $beneficiario_id = null;

        // Si no hay nombre o apellido, saltamos esta fila
        if (empty($nombre) || empty($apellido_paterno)) {
            continue;
        }

        // 1. Buscar o Crear Beneficiario
        if ($curp) {
            $stmt = $pdo->prepare("SELECT id FROM beneficiarios WHERE curp = ?");
            $stmt->execute([$curp]);
            $existente = $stmt->fetch();
            if ($existente) {
                $beneficiario_id = $existente['id'];
            }
        }
        
        // Si no se encontró por CURP (o no se proporcionó CURP), creamos uno nuevo
        if (!$beneficiario_id) {
            $sql_insert_ben = "INSERT INTO beneficiarios (nombre, apellido_paterno, curp) VALUES (?, ?, ?)";
            $stmt_insert_ben = $pdo->prepare($sql_insert_ben);
            $stmt_insert_ben->execute([$nombre, $apellido_paterno, $curp]);
            $beneficiario_id = $pdo->lastInsertId();
        }

        // 2. Insertar el registro de asistencia
        // Usamos INSERT IGNORE para evitar errores si ya existe una asistencia para ese día
        $sql_asistencia = "INSERT IGNORE INTO actividad_beneficiario (actividad_id, beneficiario_id, fecha_asistencia, observaciones) VALUES (?, ?, ?, ?)";
        $stmt_asistencia = $pdo->prepare($sql_asistencia);
        $stmt_asistencia->execute([$actividad_id, $beneficiario_id, $fecha_asistencia, $observaciones]);
    }

    $pdo->commit();
    header('Location: index.php?actividad_id=' . $actividad_id . '&success=Asistencia guardada');
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    header('Location: index.php?actividad_id=' . $actividad_id . '&error=' . urlencode($e->getMessage()));
    exit;
}
?>