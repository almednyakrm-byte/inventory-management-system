<?php

// Include database connection file
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized access'));
    exit;
}

// Get input data from JSON body
$input = json_decode(file_get_contents('php://input'), true);

// If input is empty, use POST data
if (empty($input)) {
    $input = $_POST;
}

// Define routes for CRUD operations
$routes = array(
    '/purchases' => array('GET', 'POST'),
    '/purchases/:id' => array('GET', 'PUT', 'DELETE')
);

// Get current route
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Check if route is valid
foreach ($routes as $route => $methods) {
    if (strpos($uri, $route) !== false) {
        $route = $route;
        break;
    }
}

// Check if method is valid
if (!in_array($method, $routes[$route])) {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
    exit;
}

// Validate input data
if ($method === 'POST') {
    // Validate purchase data
    $requiredFields = array('product_id', 'quantity', 'price');
    foreach ($requiredFields as $field) {
        if (!isset($input[$field]) || empty($input[$field])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }
    }
}

// Sanitize input data
if ($method === 'POST') {
    $input['product_id'] = (int) $input['product_id'];
    $input['quantity'] = (int) $input['quantity'];
    $input['price'] = (float) $input['price'];
}

// Connect to database
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);

// Handle CRUD operations
if ($method === 'GET') {
    // Get all purchases
    $stmt = $db->prepare('SELECT * FROM purchases');
    $stmt->execute();
    $purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($purchases);
} elseif ($method === 'POST') {
    // Insert new purchase
    $stmt = $db->prepare('INSERT INTO purchases (product_id, quantity, price, user_id) VALUES (:product_id, :quantity, :price, :user_id)');
    $stmt->bindParam(':product_id', $input['product_id']);
    $stmt->bindParam(':quantity', $input['quantity']);
    $stmt->bindParam(':price', $input['price']);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    http_response_code(201);
    echo json_encode(array('message' => 'Purchase created successfully'));
} elseif ($method === 'PUT') {
    // Update purchase
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    $id = (int) explode('/', $uri)[4];
    $stmt = $db->prepare('UPDATE purchases SET product_id = :product_id, quantity = :quantity, price = :price WHERE id = :id');
    $stmt->bindParam(':product_id', $input['product_id']);
    $stmt->bindParam(':quantity', $input['quantity']);
    $stmt->bindParam(':price', $input['price']);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    echo json_encode(array('message' => 'Purchase updated successfully'));
} elseif ($method === 'DELETE') {
    // Delete purchase
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    $id = (int) explode('/', $uri)[4];
    $stmt = $db->prepare('DELETE FROM purchases WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    echo json_encode(array('message' => 'Purchase deleted successfully'));
}

// Close database connection
$db = null;

?>