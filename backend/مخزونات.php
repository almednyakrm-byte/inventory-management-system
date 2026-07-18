<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/stocks' => array('GET', 'POST'),
    '/stocks/:id' => array('GET', 'PUT', 'DELETE')
);

// Get route and method from URL
$matches = array();
$pattern = '/^\/stocks(\/(\d+))?$/';
if (preg_match($pattern, $_SERVER['REQUEST_URI'], $matches)) {
    $route = $matches[0];
    $method = $_SERVER['REQUEST_METHOD'];
    $id = $matches[2];
} else {
    http_response_code(404);
    echo json_encode(array('error' => 'Not Found'));
    exit;
}

// Check if route and method are valid
if (!isset($routes[$route]) || !in_array($method, $routes[$route])) {
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
    exit;
}

// Handle GET request
if ($method == 'GET') {
    // Check if user is admin
    if ($_SESSION['role'] != 'admin' && $route == '/stocks/:id') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM stocks');
    $stmt->execute();

    // Fetch data
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Handle POST request
if ($method == 'POST') {
    // Validate input data
    if (!isset($input['name']) || !isset($input['quantity'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $name = $pdo->quote($input['name']);
    $quantity = $pdo->quote($input['quantity']);

    // Prepare SQL query
    $stmt = $pdo->prepare('INSERT INTO stocks (name, quantity) VALUES (:name, :quantity)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->execute();

    // Output data
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Stock created successfully'));
    exit;
}

// Handle PUT request
if ($method == 'PUT') {
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input['name']) || !isset($input['quantity'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = $pdo->quote($id);
    $name = $pdo->quote($input['name']);
    $quantity = $pdo->quote($input['quantity']);

    // Prepare SQL query
    $stmt = $pdo->prepare('UPDATE stocks SET name = :name, quantity = :quantity WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->execute();

    // Output data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Stock updated successfully'));
    exit;
}

// Handle DELETE request
if ($method == 'DELETE') {
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('DELETE FROM stocks WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Output data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Stock deleted successfully'));
    exit;
}