<?php
// Import database connection
require_once 'db.php';

// Initialize database connection
$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
    exit;
}

// Function to validate user role
function validateUserRole($role) {
    // For demonstration purposes, assume a logged-in user with admin role
    // Replace with actual authentication logic
    return $role === 'admin';
}

// Handle HTTP requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate user role
    if (!validateUserRole($_SERVER['HTTP_USER_ROLE'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Retrieve products
    $stmt = $pdo->prepare('SELECT * FROM products');
    $stmt->execute();
    $products = $stmt->fetchAll();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($products);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate user role
    if (!validateUserRole($_SERVER['HTTP_USER_ROLE'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($data['name']) || !isset($data['price'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Sanitize input data
    $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $price = filter_var($data['price'], FILTER_VALIDATE_FLOAT);

    // Insert product
    $stmt = $pdo->prepare('INSERT INTO products (name, price) VALUES (:name, :price)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':price', $price);
    $stmt->execute();

    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Product created successfully']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate user role
    if (!validateUserRole($_SERVER['HTTP_USER_ROLE'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($data['id']) || !isset($data['name']) || !isset($data['price'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Sanitize input data
    $id = filter_var($data['id'], FILTER_VALIDATE_INT);
    $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $price = filter_var($data['price'], FILTER_VALIDATE_FLOAT);

    // Update product
    $stmt = $pdo->prepare('UPDATE products SET name = :name, price = :price WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':price', $price);
    $stmt->execute();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Product updated successfully']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate user role
    if (!validateUserRole($_SERVER['HTTP_USER_ROLE'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Sanitize input data
    $id = filter_var($data['id'], FILTER_VALIDATE_INT);

    // Delete product
    $stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Product deleted successfully']);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}