<?php
// Debugging (optional)
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

// Define routes
$routes = [
    '/' => 'home.php',
    '/home' => 'home.php',
    '/about' => 'about.php',
    '/contact' => 'contact.php',
];

// Get URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');
if ($uri === '') $uri = '/';

// Detect if it's an AJAX request from Framework7 (router request)
$isFramework7Request = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';


// Route logic
$page = $routes[$uri] ?? null;

if ($page && file_exists(__DIR__ . "/pages/$page")) {
    // Capture output
    ob_start();
    include __DIR__ . "/pages/$page";
    $pageContent = ob_get_clean();

    if ($isFramework7Request) {
        // If Framework7 is requesting via router, return only page content
        echo $pageContent;
    } else {
        // If normal page load, include full layout with page content
        include __DIR__ . '/route.php';
    }
} else {
    http_response_code(404);
    echo "404 - Page Not Found";
}
