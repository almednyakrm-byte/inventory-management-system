<?php

// Import database connection settings
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data from JSON body
$inputData = json_decode(file_get_contents('php://input'), true);

// Define CRUD operations
$crudOperations = array(
    'GET' => 'getInventory',
    'POST' => 'createInventory',
    'PUT' => 'updateInventory',
    'DELETE' => 'deleteInventory'
);

// Get HTTP request method
$method = $_SERVER['REQUEST_METHOD'];

// Check if method is valid
if (!array_key_exists($method, $crudOperations)) {
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
    exit;
}

// Call corresponding CRUD operation
$crudOperation = $crudOperations[$method];

// Call CRUD operation
$result = $crudOperation($inputData);

// Output result
http_response_code($result['status']);
header('Content-Type: application/json');
echo json_encode($result['data']);

// CRUD operations
function getInventory($inputData) {
    // Validate input data
    if (!isset($inputData['limit']) || !isset($inputData['offset'])) {
        return array('status' => 400, 'data' => array('error' => 'Invalid input data'));
    }

    // Sanitize input data
    $limit = intval($inputData['limit']);
    $offset = intval($inputData['offset']);

    // Prepare SQL query
    $sql = "SELECT * FROM inventory LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch results
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return result
    return array('status' => 200, 'data' => $data);
}

function createInventory($inputData) {
    // Validate input data
    if (!isset($inputData['name']) || !isset($inputData['quantity'])) {
        return array('status' => 400, 'data' => array('error' => 'Invalid input data'));
    }

    // Sanitize input data
    $name = $pdo->quote($inputData['name']);
    $quantity = intval($inputData['quantity']);

    // Prepare SQL query
    $sql = "INSERT INTO inventory (name, quantity) VALUES ($name, :quantity)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->execute();

    // Return result
    return array('status' => 201, 'data' => array('message' => 'Inventory created successfully'));
}

function updateInventory($inputData) {
    // Validate input data
    if (!isset($inputData['id']) || !isset($inputData['name']) || !isset($inputData['quantity'])) {
        return array('status' => 400, 'data' => array('error' => 'Invalid input data'));
    }

    // Sanitize input data
    $id = intval($inputData['id']);
    $name = $pdo->quote($inputData['name']);
    $quantity = intval($inputData['quantity']);

    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        return array('status' => 403, 'data' => array('error' => 'Forbidden'));
    }

    // Prepare SQL query
    $sql = "UPDATE inventory SET name = $name, quantity = :quantity WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->execute();

    // Return result
    return array('status' => 200, 'data' => array('message' => 'Inventory updated successfully'));
}

function deleteInventory($inputData) {
    // Validate input data
    if (!isset($inputData['id'])) {
        return array('status' => 400, 'data' => array('error' => 'Invalid input data'));
    }

    // Sanitize input data
    $id = intval($inputData['id']);

    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        return array('status' => 403, 'data' => array('error' => 'Forbidden'));
    }

    // Prepare SQL query
    $sql = "DELETE FROM inventory WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Return result
    return array('status' => 200, 'data' => array('message' => 'Inventory deleted successfully'));
}

?>