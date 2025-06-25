<?php
header('Content-Type: application/json');
require_once '../includes/config.php';

$curp = trim($_GET['curp'] ?? '');

if (empty($curp)) {
    echo json_encode([]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM beneficiarios WHERE curp = ?");
    $stmt->execute([strtoupper($curp)]);
    $beneficiario = $stmt->fetch();
    
    echo json_encode($beneficiario ?: null);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la base de datos']);
}
?>