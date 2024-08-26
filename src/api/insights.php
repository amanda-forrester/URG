<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

try {
    $pdo = getDbConnection();

    if (!$pdo) {
        throw new Exception('Failed to connect to the database.');
    }

    // Fetch total parts
    $stmt = $pdo->query("SELECT COUNT(*) as total_parts FROM parts");
    $totalParts = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch available parts
    $stmt = $pdo->query("SELECT COUNT(*) as available_parts FROM parts WHERE status = 'available'");
    $availableParts = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch sold parts
    $stmt = $pdo->query("SELECT COUNT(*) as sold_parts FROM parts WHERE status = 'sold'");
    $soldParts = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch requests fulfilled
    $stmt = $pdo->query("SELECT COUNT(*) as fulfilled_requests FROM requests WHERE fulfilled = true");
    $fulfilledRequests = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch most recycled parts
    $stmt = $pdo->query("
        SELECT p.part_name, COUNT(*) as recycled_count 
        FROM requests r
        JOIN parts p ON r.part_id = p.id
        GROUP BY p.part_name
        ORDER BY recycled_count DESC
        LIMIT 5");
    $mostRecycledParts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch average time parts stay in inventory before being sold
    $stmt = $pdo->query("
        SELECT ROUND(AVG(EXTRACT(day FROM AGE(r.request_date, p.date_added))), 2) as avg_days_in_inventory 
        FROM requests r
        JOIN parts p ON r.part_id = p.id
        WHERE r.fulfilled = true");
    $avgDaysInInventory = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch parts by vehicle make
    $stmt = $pdo->query("
        SELECT vehicle_make, COUNT(*) as count
        FROM parts
        GROUP BY vehicle_make
    ");
    $partsByMake = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate environmental impact
    $co2Saved = ($soldParts['sold_parts'] ?? 0) * 50; // Assuming 50 kg CO2 saved per part
    $landfillSaved = ($soldParts['sold_parts'] ?? 0) * 0.01; // Assuming 0.01 cubic meters saved per part
    $energySaved = ($soldParts['sold_parts'] ?? 0) * 100; // Assuming 100 kWh saved per part

    // Prepare insights data
    $insights = [
        'total_parts' => $totalParts['total_parts'] ?? 0,
        'available_parts' => $availableParts['available_parts'] ?? 0,
        'sold_parts' => $soldParts['sold_parts'] ?? 0,
        'fulfilled_requests' => $fulfilledRequests['fulfilled_requests'] ?? 0,
        'most_recycled_parts' => $mostRecycledParts,
        'avg_days_in_inventory' => $avgDaysInInventory['avg_days_in_inventory'] ?? 0,
        'parts_by_make' => $partsByMake,
        'co2_saved' => $co2Saved,
        'landfill_saved' => $landfillSaved,
        'energy_saved' => $energySaved,
    ];

    echo json_encode($insights);

} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    echo json_encode(['error' => 'Database error occurred.']);
} catch (Exception $e) {
    error_log('Unexpected error: ' . $e->getMessage());
    echo json_encode(['error' => 'An unexpected error occurred.']);
}
