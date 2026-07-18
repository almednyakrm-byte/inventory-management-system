<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get all inventory items
    $stmt = $pdo->prepare('SELECT * FROM inventory');
    $stmt->execute();
    $inventory = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return inventory items
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($inventory);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input['name']) || !isset($input['quantity']) || !isset($input['price'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $quantity = filter_var($input['quantity'], FILTER_SANITIZE_NUMBER_INT);
    $price = filter_var($input['price'], FILTER_SANITIZE_NUMBER_FLOAT);

    // Insert new inventory item
    $stmt = $pdo->prepare('INSERT INTO inventory (name, quantity, price) VALUES (:name, :quantity, :price)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':price', $price);
    $stmt->execute();

    // Return new inventory item
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Inventory item created successfully'));
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['quantity']) || !isset($input['price'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $quantity = filter_var($input['quantity'], FILTER_SANITIZE_NUMBER_INT);
    $price = filter_var($input['price'], FILTER_SANITIZE_NUMBER_FLOAT);

    // Update existing inventory item
    $stmt = $pdo->prepare('UPDATE inventory SET name = :name, quantity = :quantity, price = :price WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':price', $price);
    $stmt->execute();

    // Return updated inventory item
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Inventory item updated successfully'));
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete inventory item
    $stmt = $pdo->prepare('DELETE FROM inventory WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Inventory item deleted successfully'));
    exit;
}

// Return error message for invalid request method
http_response_code(405);
echo json_encode(array('error' => 'Method not allowed'));
exit;