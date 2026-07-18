<?php
require_once 'db.php';

// Get user role and authentication status
$userRole = $_SESSION['userRole'] ?? null;
$authenticated = $_SESSION['authenticated'] ?? false;

// Check if user is authenticated and authorized
if (!$authenticated) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare('SELECT * FROM products');
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($products);
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Validate and sanitize input data
    $name = trim($data['name'] ?? '');
    $description = trim($data['description'] ?? '');
    $price = (float) ($data['price'] ?? 0);
    $quantity = (int) ($data['quantity'] ?? 0);

    if (empty($name) || empty($description) || $price <= 0 || $quantity <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid product data']);
        exit;
    }

    // Prepare and execute INSERT query
    $stmt = $pdo->prepare('INSERT INTO products (name, description, price, quantity) VALUES (:name, :description, :price, :quantity)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->execute();

    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Product created successfully']);
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Validate and sanitize input data
    $id = (int) ($data['id'] ?? 0);
    $name = trim($data['name'] ?? '');
    $description = trim($data['description'] ?? '');
    $price = (float) ($data['price'] ?? 0);
    $quantity = (int) ($data['quantity'] ?? 0);

    if (empty($name) || empty($description) || $price <= 0 || $quantity <= 0 || $id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid product data']);
        exit;
    }

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden access']);
        exit;
    }

    // Prepare and execute UPDATE query
    $stmt = $pdo->prepare('UPDATE products SET name = :name, description = :description, price = :price, quantity = :quantity WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->execute();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Product updated successfully']);
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Validate and sanitize input data
    $id = (int) ($data['id'] ?? 0);

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid product ID']);
        exit;
    }

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden access']);
        exit;
    }

    // Prepare and execute DELETE query
    $stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Product deleted successfully']);
}