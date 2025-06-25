<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger todos los datos del formulario
    $producto_id = (int)($_POST['producto_id'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $fecha_inicio = $_POST['fecha_inicio'] ?? null;
    $fecha_fin = $_POST['fecha_fin'] ?? null;
    $responsable_id = !empty($_POST['responsable_id']) ? (int)$_POST['responsable_id'] : null;
    $estatus = trim($_POST['estatus'] ?? 'Programada');
    $meta = trim($_POST['meta'] ?? '');
    $indicador = trim($_POST['indicador'] ?? '');

    if (empty($nombre) || $producto_id === 0 || empty($fecha_inicio) || empty($fecha_fin)) {
        die("Nombre, producto padre y fechas son obligatorios.");
    }

    try {
        $sql = "INSERT INTO actividades (producto_id, nombre, fecha_inicio, fecha_fin, responsable_id, estatus, meta, indicador) 
                VALUES (:producto_id, :nombre, :fecha_inicio, :fecha_fin, :responsable_id, :estatus, :meta, :indicador)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':producto_id' => $producto_id,
            ':nombre' => $nombre,
            ':fecha_inicio' => $fecha_inicio,
            ':fecha_fin' => $fecha_fin,
            ':responsable_id' => $responsable_id,
            ':estatus' => $estatus,
            ':meta' => $meta,
            ':indicador' => $indicador
        ]);
        
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        die("Error al guardar la actividad: " . $e->getMessage());
    }
} else {
    header('Location: index.php');
    exit;
}
?>