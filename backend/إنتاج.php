<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

// Validate input data
if (!isset($input['id']) && !isset($input['name']) && !isset($input['description'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request data']);
    exit;
}

// Connect to database
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET request
if (isset($_GET['id'])) {
    // Get single record by ID
    $stmt = $db->prepare('SELECT * FROM إنتاج WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($record) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($record);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Record not found']);
    }
} elseif (isset($_GET['all'])) {
    // Get all records
    $stmt = $db->prepare('SELECT * FROM إنتاج');
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($records);
} else {
    // Handle POST, PUT, DELETE requests
    if (isset($input['id'])) {
        // Check if user is admin
        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }
        
        // Update record
        $stmt = $db->prepare('UPDATE إنتاج SET name = :name, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':description', $input['description']);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Record updated successfully']);
    } elseif (isset($input['name']) && isset($input['description'])) {
        // Check if user is admin
        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }
        
        // Insert new record
        $stmt = $db->prepare('INSERT INTO إنتاج (name, description) VALUES (:name, :description)');
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':description', $input['description']);
        $stmt->execute();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Record created successfully']);
    } else {
        // Delete record
        $stmt = $db->prepare('DELETE FROM إنتاج WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Record deleted successfully']);
    }
}

$db = null;