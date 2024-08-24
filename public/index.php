<?php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Handle API routes
if (strpos($uri, '/api/') === 0) {
    if ($uri === '/api/insights') {
        require_once __DIR__ . '/../src/api/insights.php';
    } elseif ($uri === '/api/parts') {
        require_once __DIR__ . '/../src/api/parts.php';
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'API Not Found']);
    }
} else {
    // Handle frontend routes
    if ($uri === '/' || $uri === '/dashboard') {
        require_once __DIR__ . '/dashboard.php'; // Adjusted path
    } else {
        http_response_code(404);
        echo "Not Found";
    }
}

