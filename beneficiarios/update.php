<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);

    // Lista de todos los campos posibles en el formulario
    $campos = [
        'nombre', 'apellido_paterno', 'apellido_materno', 'fecha_nacimiento', 
        'sexo', 'curp', 'telefono', 'email', 'escolaridad', 'ocupacion', 
        'colonia', 'calle_numero', 'codigo_postal', 'municipio'
    ];
    
    $datos = [];
    foreach ($campos as $campo) {
        // Usamos el operador de fusión de null para manejar campos vacíos como null
        $datos[$campo] = !empty($_POST[$campo]) ? trim($_POST[$campo]) : null;
    }

    if ($id === 0 || empty($datos['nombre']) || empty($datos['apellido_paterno'])) {
        die("Datos inválidos. El ID, Nombre y Apellido Paterno son obligatorios.");
    }
    
    // Convertir CURP a mayúsculas si existe
    if ($datos['curp']) {
        $datos['curp'] = strtoupper($datos['curp']);
    }

    try {
        // Construir la parte SET de la consulta dinámicamente
        $sql_parts = [];
        foreach (array_keys($datos) as $columna) {
            $sql_parts[] = "{$columna} = :{$columna}";
        }
        $sql_update_part = implode(', ', $sql_parts);
        
        $sql = "UPDATE beneficiarios SET {$sql_update_part} WHERE id = :id";
        
        // Añadir el ID al array de datos para el binding en el WHERE
        $datos['id'] = $id; 
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($datos);
        
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        // Manejar error de CURP duplicado
        if ($e->getCode() == 23000) {
            die("Error al actualizar: Ya existe otro beneficiario con esa CURP. Por favor, verifique los datos.");
        }
        die("Error al actualizar el beneficiario: " . $e->getMessage());
    }
} else {
    header('Location: index.php');
    exit;
}
?>