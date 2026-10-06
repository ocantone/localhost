<?php
header('Content-Type: application/json; charset=utf-8');

$dni = $_GET['dni'] ?? null;

if (!$dni) {
    /* Crea un objeto JSON y lo envia ha la respuesta HTTP */
    echo json_encode(['status' => 'error', 'message' => 'DNI no proporcionado']);
    exit;
}

$host = 'db';
$db   = 'datos';
$user = 'prog3';
$pass = 'admin123';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $stmt = $pdo->prepare('SELECT dni, nombre, email FROM clientes WHERE dni = ?');
    $stmt->execute([$dni]);
    $cliente = $stmt->fetch();
/*Pude hacer la consulta */
    if ($cliente) {   /*  Lo encontró */
        echo json_encode(['status' => 'success', 'data' => $cliente]);
    } else {    /*  No lo encontró*/
        echo json_encode(['status' => 'not_found', 'message' => 'Cliente no encontrado']);
    }
/* No pude hacer la consulta*/
} catch (PDOException $e) {   
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos']);
}