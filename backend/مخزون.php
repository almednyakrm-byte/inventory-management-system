<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required parameter: id']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM مخزون WHERE id = :id');
    $stmt->bindParam(':id', $inputData['id']);
    $stmt->execute();

    // Fetch and return data
    $data = $stmt->fetch();
    if ($data) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Resource not found']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    if (!isset($inputData['name']) || !isset($inputData['quantity'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required parameters: name, quantity']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('INSERT INTO مخزون (name, quantity) VALUES (:name, :quantity)');
    $stmt->bindParam(':name', $inputData['name']);
    $stmt->bindParam(':quantity', $inputData['quantity']);
    $stmt->execute();

    // Return created resource
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $pdo->lastInsertId()]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate and sanitize input
    if (!isset($inputData['id']) || !isset($inputData['name']) || !isset($inputData['quantity'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required parameters: id, name, quantity']);
        exit;
    }

    // Check user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('UPDATE مخزون SET name = :name, quantity = :quantity WHERE id = :id');
    $stmt->bindParam(':id', $inputData['id']);
    $stmt->bindParam(':name', $inputData['name']);
    $stmt->bindParam(':quantity', $inputData['quantity']);
    $stmt->execute();

    // Return updated resource
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Resource updated successfully']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate and sanitize input
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required parameter: id']);
        exit;
    }

    // Check user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('DELETE FROM مخزون WHERE id = :id');
    $stmt->bindParam(':id', $inputData['id']);
    $stmt->execute();

    // Return deleted resource
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Resource deleted successfully']);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}