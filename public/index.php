<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($uri === '/api/insights') {
    require_once __DIR__ . '/../src/api/insights.php';
} elseif ($uri === '/api/parts') {
    require_once __DIR__ . '/../src/api/parts.php';
} else {
    http_response_code(404);
    echo "Not Found";
}
