<?php
// Import database connection
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get user role
$userRole = $_SESSION['user_role'];

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $warehouseId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    if ($warehouseId === false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid warehouse ID']);
        exit;
    }

    // Prepare and execute SQL query to retrieve warehouse data
    $stmt = $pdo->prepare('SELECT * FROM warehouses WHERE id = :id');
    $stmt->bindParam(':id', $warehouseId);
    $stmt->execute();
    $warehouseData = $stmt->fetch();

    // Process output
    if ($warehouseData === false) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Warehouse not found']);
    } else {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($warehouseData);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $inputData = json_decode(file_get_contents('php://input'), true);
    if ($inputData === null) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Check if user has admin role
    if ($userRole !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare and execute SQL query to create new warehouse
    $stmt = $pdo->prepare('INSERT INTO warehouses (name, address, capacity) VALUES (:name, :address, :capacity)');
    $stmt->bindParam(':name', $inputData['name']);
    $stmt->bindParam(':address', $inputData['address']);
    $stmt->bindParam(':capacity', $inputData['capacity']);
    $stmt->execute();

    // Process output
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Warehouse created successfully']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate and sanitize input
    $inputData = json_decode(file_get_contents('php://input'), true);
    if ($inputData === null) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Check if user has admin role
    if ($userRole !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare and execute SQL query to update existing warehouse
    $stmt = $pdo->prepare('UPDATE warehouses SET name = :name, address = :address, capacity = :capacity WHERE id = :id');
    $stmt->bindParam(':id', $inputData['id']);
    $stmt->bindParam(':name', $inputData['name']);
    $stmt->bindParam(':address', $inputData['address']);
    $stmt->bindParam(':capacity', $inputData['capacity']);
    $stmt->execute();

    // Process output
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Warehouse not found']);
    } else {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Warehouse updated successfully']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate and sanitize input
    $warehouseId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    if ($warehouseId === false) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid warehouse ID']);
        exit;
    }

    // Check if user has admin role
    if ($userRole !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare and execute SQL query to delete existing warehouse
    $stmt = $pdo->prepare('DELETE FROM warehouses WHERE id = :id');
    $stmt->bindParam(':id', $warehouseId);
    $stmt->execute();

    // Process output
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Warehouse not found']);
    } else {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Warehouse deleted successfully']);
    }
} else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}