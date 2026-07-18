<?php
require_once 'db.php';

// Get user role and authentication status
if (!isset($_SESSION['role']) || !isset($_SESSION['logged_in'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

// Validate input data
if (!isset($input['id']) && !isset($input['name']) && !isset($input['description']) && !isset($input['amount'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request data']);
    exit;
}

// Prepare database connection
$stmt = $pdo->prepare('
    SELECT * FROM فواتير
');

// Handle GET request
if (isset($_GET['id'])) {
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $result = $stmt->fetch();
    if ($result) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
    }
} elseif (isset($_GET['all'])) {
    $stmt->execute();
    $results = $stmt->fetchAll();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($results);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request method']);
}

// Handle POST request
if (isset($input['name']) && isset($input['description']) && isset($input['amount'])) {
    $stmt = $pdo->prepare('
        INSERT INTO فواتير (name, description, amount)
        VALUES (:name, :description, :amount)
    ');
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->bindParam(':amount', $input['amount']);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Created successfully']);
}

// Handle PUT request
if (isset($input['id']) && isset($input['name']) && isset($input['description']) && isset($input['amount'])) {
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    $stmt = $pdo->prepare('
        UPDATE فواتير
        SET name = :name, description = :description, amount = :amount
        WHERE id = :id
    ');
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->bindParam(':amount', $input['amount']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Updated successfully']);
}

// Handle DELETE request
if (isset($input['id'])) {
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    $stmt = $pdo->prepare('
        DELETE FROM فواتير
        WHERE id = :id
    ');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Deleted successfully']);
}