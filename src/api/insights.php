<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

$pdo = getDbConnection();

// Fetch total parts
$stmt = $pdo->query("SELECT COUNT(*) as total_parts FROM parts");
$totalParts = $stmt->fetch();

// Fetch available parts
$stmt = $pdo->query("SELECT COUNT(*) as available_parts FROM parts WHERE status = 'available'");
$availableParts = $stmt->fetch();

// Fetch sold parts
$stmt = $pdo->query("SELECT COUNT(*) as sold_parts FROM parts WHERE status = 'sold'");
$soldParts = $stmt->fetch();

// Fetch requests fulfilled
$stmt = $pdo->query("SELECT COUNT(*) as fulfilled_requests FROM requests WHERE fulfilled = true");
$fulfilledRequests = $stmt->fetch();

// Fetch most recycled parts
$stmt = $pdo->query("
    SELECT p.part_name, COUNT(*) as recycled_count 
    FROM requests r
    JOIN parts p ON r.part_id = p.id
    GROUP BY p.part_name
    ORDER BY recycled_count DESC
    LIMIT 5");
$mostRecycledParts = $stmt->fetchAll();

// Fetch average time parts stay in inventory before being sold
$stmt = $pdo->query("
    SELECT AVG(EXTRACT(day FROM AGE(r.request_date, p.date_added))) as avg_days_in_inventory 
    FROM requests r
    JOIN parts p ON r.part_id = p.id
    WHERE r.fulfilled = true");
$avgDaysInInventory = $stmt->fetch();

// Fetch parts by vehicle make
$stmt = $pdo->query("
    SELECT vehicle_make, COUNT(*) as count
    FROM parts
    GROUP BY vehicle_make
");
$partsByMake = $stmt->fetchAll();


// Prepare insights data
$insights = [
    'total_parts' => $totalParts['total_parts'],
    'available_parts' => $availableParts['available_parts'],
    'sold_parts' => $soldParts['sold_parts'],
    'fulfilled_requests' => $fulfilledRequests['fulfilled_requests'],
    'most_recycled_parts' => $mostRecycledParts,
    'avg_days_in_inventory' => $avgDaysInInventory['avg_days_in_inventory'],
    'parts_by_make' => $partsByMake,
];

echo json_encode($insights);



