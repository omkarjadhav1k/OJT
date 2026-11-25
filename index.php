<?php

require_once __DIR__ . '/Config/database.php';
require_once __DIR__ . '/Config/helpers.php';

startSession();
loadAppFiles();

$parts  = getUrlParts();
$page   = strtolower($parts[0] ?? '');
$action = strtolower($parts[1] ?? '');
$id     = getRouteId($parts);
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($page === '') {
    $page = 'login';
}

routeRequest($page, $action, $id, $method);

// Load all model and controller classes
function loadAppFiles() {
    $models = ['User', 'Category', 'Product', 'Customer', 'Order', 'Sale'];
    foreach ($models as $model) {
        require_once __DIR__ . '/app/Models/' . $model . '.php';
    }

    $controllers = [
        'UserController',
        'CategoryController',
        'ProductController',
        'CustomerController',
        'OrderController',
        'SaleController'
    ];
    foreach ($controllers as $controller) {
        require_once __DIR__ . '/app/Controllers/' . $controller . '.php';
    }
}

// Read the URL path into simple segments
function getUrlParts() {
    $url = $_GET['url'] ?? '';

    if ($url === '' && !empty($_SERVER['REQUEST_URI'])) {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');

        if ($base !== '' && strpos($path, $base) === 0) {
            $path = substr($path, strlen($base));
        }
        $url = $path;
    }

    $url = trim($url, '/');
    if ($url === '' || strtolower($url) === 'index.php') {
        return [];
    }

    return explode('/', $url);
}

// Get numeric ID from URL like /products/5 or /products/update/5
function getRouteId($parts) {
    if (!empty($parts[2]) && is_numeric($parts[2])) {
        return (int)$parts[2];
    }

    if (!empty($parts[1]) && is_numeric($parts[1])) {
        return (int)$parts[1];
    }

    return 0;
}

// Send request to the matching page or controller
function routeRequest($page, $action, $id, $method) {
    if ($page === 'home' || $page === 'login') {
        handleLogin($method);
        return;
    }

    if ($page === 'logout') {
        $user = new UserController();
        $user->logout();
        return;
    }

    if ($page === 'users') {
        handleUsers($action, $method);
        return;
    }

    $controllers = [
        'categories' => 'CategoryController',
        'products'   => 'ProductController',
        'customers'  => 'CustomerController',
        'orders'     => 'OrderController',
        'sales'      => 'SaleController'
    ];

    if (isset($controllers[$page])) {
        $controllerClass = $controllers[$page];
        $result = handleCrud(new $controllerClass(), $action, $id, $method);

        if ($result !== null) {
            sendJson($result === false ? ['success' => false, 'message' => 'Record not found.'] : $result);
        }
        return;
    }

    showNotFound($page);
}

function handleLogin($method) {
    $user = new UserController();

    if ($method === 'POST') {
        $user->login();
    } else {
        $user->showLogin();
    }
}

function handleUsers($action, $method) {
    $user = new UserController();

    if ($action === 'logout') {
        $user->logout();
        return;
    }

    if ($action === 'register' && $method === 'POST') {
        $result = $user->register();
        if ($result !== null) {
            sendJson($result);
        }
        return;
    }

    if ($action === 'list' && $method === 'GET') {
        sendJson($user->getAll());
        return;
    }

    handleLogin($method);
}

function handleCrud($controller, $action, $id, $method) {
    if ($action === 'delete' && $id > 0) {
        return $controller->delete($id);
    }

    if ($action === 'update' && $id > 0 && $method === 'POST' && method_exists($controller, 'update')) {
        return $controller->update($id);
    }

    if ($action === 'create' && $method === 'POST') {
        return $controller->create();
    }

    if ($id > 0) {
        if ($method === 'DELETE') {
            return $controller->delete($id);
        }
        if (($method === 'POST' || $method === 'PUT') && method_exists($controller, 'update')) {
            return $controller->update($id);
        }
        return $controller->getById($id);
    }

    if ($method === 'POST') {
        return $controller->create();
    }

    return $controller->getAll();
}

function sendJson($data) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
}

function showNotFound($page) {
    http_response_code(404);
    echo "Page not found: " . clean($page);
}
