<?php
// index.php - Front Controller & Router

// CORS Headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Load Config & Helpers
require_once __DIR__ . '/Config/database.php';
require_once __DIR__ . '/Config/helpers.php';

// Load Models
require_once __DIR__ . '/app/Models/User.php';
require_once __DIR__ . '/app/Models/Category.php';
require_once __DIR__ . '/app/Models/Product.php';
require_once __DIR__ . '/app/Models/Customer.php';
require_once __DIR__ . '/app/Models/Order.php';
require_once __DIR__ . '/app/Models/Sale.php';

// Load Controllers
require_once __DIR__ . '/app/Controllers/UserController.php';
require_once __DIR__ . '/app/Controllers/CategoryController.php';
require_once __DIR__ . '/app/Controllers/ProductController.php';
require_once __DIR__ . '/app/Controllers/CustomerController.php';
require_once __DIR__ . '/app/Controllers/OrderController.php';
require_once __DIR__ . '/app/Controllers/SaleController.php';

// Parse URL and Request Method
$url = trim($_GET['url'] ?? '', '/');
$parts = $url ? explode('/', $url) : [];
$resource = strtolower($parts[0] ?? '');
$param = $parts[1] ?? null;
$method = $_SERVER['REQUEST_METHOD'];

// Support method override for HTML forms / testing (_method = PUT / DELETE)
$requestData = getRequestData();
if (!empty($requestData['_method'])) {
    $method = strtoupper($requestData['_method']);
}

// Router
switch ($resource) {
    case '':
    case 'home':
        jsonResponse('success', 'Bakery Management Backend API is active', [
            'routes' => [
                'Users'      => ['GET /users', 'POST /users/register', 'POST /users/login'],
                'Categories' => ['GET /categories', 'GET /categories/{id}', 'POST /categories', 'PUT /categories/{id}', 'DELETE /categories/{id}'],
                'Products'   => ['GET /products', 'GET /products/{id}', 'POST /products', 'PUT /products/{id}', 'DELETE /products/{id}'],
                'Customers'  => ['GET /customers', 'GET /customers/{id}', 'POST /customers', 'PUT /customers/{id}', 'DELETE /customers/{id}'],
                'Orders'     => ['GET /orders', 'GET /orders/{id}', 'POST /orders', 'PUT /orders/{id}', 'DELETE /orders/{id}'],
                'Sales'      => ['GET /sales', 'GET /sales/{id}', 'POST /sales', 'DELETE /sales/{id}']
            ]
        ]);
        break;

    case 'users':
        $controller = new UserController();
        if ($param === 'register' && $method === 'POST') {
            $controller->register();
        } elseif ($param === 'login' && $method === 'POST') {
            $controller->login();
        } elseif (!$param && $method === 'GET') {
            $controller->getAll();
        } else {
            jsonResponse('error', 'Invalid route or method for /users', null, 404);
        }
        break;

    case 'categories':
        $controller = new CategoryController();
        handleCrudRoutes($controller, $method, $param, $parts);
        break;

    case 'products':
        $controller = new ProductController();
        handleCrudRoutes($controller, $method, $param, $parts);
        break;

    case 'customers':
        $controller = new CustomerController();
        handleCrudRoutes($controller, $method, $param, $parts);
        break;

    case 'orders':
        $controller = new OrderController();
        handleCrudRoutes($controller, $method, $param, $parts);
        break;

    case 'sales':
        $controller = new SaleController();
        handleCrudRoutes($controller, $method, $param, $parts);
        break;

    default:
        jsonResponse('error', 'Endpoint not found: ' . $resource, null, 404);
        break;
}

// Helper for standard CRUD routing: GET, POST, PUT, DELETE
function handleCrudRoutes(object $controller, string $method, ?string $param, array $parts): void {
    // Check if URL has action format like: products/delete/5 or products/update/5
    if ($param === 'delete' && !empty($parts[2])) {
        $controller->delete((int)$parts[2]);
        return;
    }
    if ($param === 'update' && !empty($parts[2])) {
        $controller->update((int)$parts[2]);
        return;
    }

    // Standard REST routing
    if (!$param) {
        if ($method === 'GET') {
            $controller->getAll();
        } elseif ($method === 'POST') {
            $controller->create();
        }
    } elseif (is_numeric($param)) {
        $id = (int)$param;
        if ($method === 'GET') {
            $controller->getById($id);
        } elseif ($method === 'PUT' || $method === 'POST') {
            $controller->update($id);
        } elseif ($method === 'DELETE') {
            $controller->delete($id);
        }
    }

    jsonResponse('error', 'Method not allowed or invalid route', null, 405);
}
