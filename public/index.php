<?php
session_start();

// Autoloader
spl_autoload_register(function ($class) {
    // Convert namespace to full file path
    $prefix = '';
    $base_dir = __DIR__ . '/../src/';
    
    // Check if class uses the namespace prefix
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Simple Router
$request_uri = $_SERVER['REQUEST_URI'];
$script_name = $_SERVER['SCRIPT_NAME'];

// Get the directory of the script (e.g., /sam/public)
$dir = dirname($script_name);
$base_url = str_replace('\\', '/', $dir); 
// Ensure no trailing slash unless it's just /
if ($base_url !== '/') $base_url = rtrim($base_url, '/');
define('BASE_URL', $base_url);

// Remove script path (base_url) from URI to get the relative route path
// If defined base_url is /sam/public, and URI is /sam/public/login, result is /login
if (strpos($request_uri, $base_url) === 0) {
    $path = substr($request_uri, strlen($base_url));
} else {
    $path = $request_uri;
}

$path = trim($path, '/');
$path = parse_url($path, PHP_URL_PATH); // Remove query string

// Define Routes (keys are relative paths)
$routes = [
    '' => ['Controllers\AuthController', 'login'],
    'login' => ['Controllers\AuthController', 'login'],
    'logout' => ['Controllers\AuthController', 'logout'],
    'register' => ['Controllers\AuthController', 'register'],
    'dashboard' => ['Controllers\DashboardController', 'index'],
    'profile' => ['Controllers\ProfileController', 'index'],
    'profile/update' => ['Controllers\ProfileController', 'update'],
    'logs/history' => ['Controllers\LogController', 'history'],
    'logs/daily' => ['Controllers\LogController', 'daily'],
    'logs/diet' => ['Controllers\LogController', 'diet'],
    'logs/exercise' => ['Controllers\LogController', 'exercise'],
    'logs/save' => ['Controllers\LogController', 'save'],
    'logs/delete' => ['Controllers\LogController', 'delete'],
    'chemo' => ['Controllers\ChemoController', 'index'],
    'chemo/store' => ['Controllers\ChemoController', 'store'],
    'chemo/delete' => ['Controllers\ChemoController', 'delete'],
    'lab' => ['Controllers\LabController', 'index'],
    'lab/store' => ['Controllers\LabController', 'store'],
    'lab/delete' => ['Controllers\LabController', 'delete'],
    'export' => ['Controllers\ReportController', 'index'],
];

// Dispatch
if (array_key_exists($path, $routes)) {
    [$controllerName, $method] = $routes[$path];
    $controller = new $controllerName();
    $controller->$method();
} else {
    // 404
    http_response_code(404);
    echo "404 Not Found";
}
