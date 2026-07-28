<?php
require_once 'db.php';

// Get user data from session
$user = $_SESSION['user'];

// Check if user is logged in
if (!$user) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Check if input is valid
if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

// Define database table name
$tableName = 'صفقات';

// Define columns
$columns = [
    'id' => 'id',
    'title' => 'title',
    'description' => 'description',
    'price' => 'price',
    'created_at' => 'created_at',
    'updated_at' => 'updated_at'
];

// Define allowed roles for CRUD operations
$allowedRoles = [
    'GET' => ['user'],
    'POST' => ['user'],
    'PUT' => ['admin'],
    'DELETE' => ['admin']
];

// Check if user has permission to perform the requested action
if (!isset($allowedRoles[$_SERVER['REQUEST_METHOD']]) || !in_array($user['role'], $allowedRoles[$_SERVER['REQUEST_METHOD']])) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Prepare SQL query to select all records from the table
        $stmt = $pdo->prepare("SELECT * FROM $tableName");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return HTTP response with JSON data
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate and sanitize input data
        $validatedData = [];
        foreach ($columns as $column => $name) {
            if (isset($input[$name])) {
                $validatedData[$column] = $pdo->quote($input[$name]);
            }
        }
        
        // Prepare SQL query to insert a new record into the table
        $stmt = $pdo->prepare("INSERT INTO $tableName SET " . implode(', ', array_map(function($column, $value) { return "$column = $value"; }, array_keys($validatedData), $validatedData)));
        $stmt->execute();
        
        // Return HTTP response with JSON data
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['id' => $pdo->lastInsertId()]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    try {
        // Validate and sanitize input data
        $validatedData = [];
        foreach ($columns as $column => $name) {
            if (isset($input[$name])) {
                $validatedData[$column] = $pdo->quote($input[$name]);
            }
        }
        
        // Prepare SQL query to update a record in the table
        $stmt = $pdo->prepare("UPDATE $tableName SET " . implode(', ', array_map(function($column, $value) { return "$column = $value"; }, array_keys($validatedData), $validatedData)) . " WHERE id = :id");
        $stmt->bindParam(':id', $input['id']);
        $stmt->execute();
        
        // Return HTTP response with JSON data
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Record updated successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    try {
        // Prepare SQL query to delete a record from the table
        $stmt = $pdo->prepare("DELETE FROM $tableName WHERE id = :id");
        $stmt->bindParam(':id', $input['id']);
        $stmt->execute();
        
        // Return HTTP response with JSON data
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Record deleted successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}