<?php
session_start();

// Display errors for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection
include_once  './includes/db.php';
include_once  './handlers/responseHandler.php';
include_once  './handlers/functions.php';

// Set the header for JSON response
header('Content-Type: application/json');

// Get and sanitize the requested URI
$requestUri = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);

// Extract the API endpoint path (assuming API_PATH is defined)
$endpoint = str_replace(API_PATH, '', $requestUri);

// Define routes
$routes = [
    '/' => __DIR__ . '/routes/welcome.php',
];

// Switch case for routing
switch (true) {
    case isset($routes[$endpoint]) && file_exists($routes[$endpoint]):
        require_once $routes[$endpoint];
        break;

    case isset($routes[$endpoint]) && !file_exists($routes[$endpoint]):
        sendErrorResponse("Route file not found: {$endpoint}");
        break;

    default:
        sendErrorResponse("Endpoint not found");
}

// Helper function to send error responses
function sendErrorResponse($message, $statusCode = 404)
{
    http_response_code($statusCode);
    echo json_encode([
        "status" => "error",
        "message" => $message
    ]);
    exit;
}
