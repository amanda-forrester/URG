<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

try {
    $pdo = getDbConnection();

    // Example: Retrieve all parts
    $stmt = $pdo->prepare("SELECT * FROM parts");
    $stmt->execute();
    $parts = $stmt->fetchAll();

    echo json_encode($parts);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
