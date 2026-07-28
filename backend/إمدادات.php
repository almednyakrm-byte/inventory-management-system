<?php

// Import database connection settings
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Validate input data
if (empty($input)) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
    exit;
}

// Define database table name
$table_name = 'إمدادات';

// Define PDO connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);

// Define CRUD operations
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Get all records
        $stmt = $pdo->prepare("SELECT * FROM $table_name");
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($records);
        break;

    case 'POST':
        // Validate input data
        if (!isset($input['name']) || !isset($input['quantity'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }

        // Sanitize input data
        $name = $pdo->quote($input['name']);
        $quantity = $pdo->quote($input['quantity']);

        // Insert new record
        $stmt = $pdo->prepare("INSERT INTO $table_name (name, quantity) VALUES ($name, $quantity)");
        $stmt->execute();
        http_response_code(201);
        echo json_encode(array('message' => 'Record created successfully'));
        break;

    case 'PUT':
        // Validate input data
        if (!isset($input['id']) || !isset($input['name']) || !isset($input['quantity'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }

        // Sanitize input data
        $id = $pdo->quote($input['id']);
        $name = $pdo->quote($input['name']);
        $quantity = $pdo->quote($input['quantity']);

        // Update existing record
        $stmt = $pdo->prepare("UPDATE $table_name SET name = $name, quantity = $quantity WHERE id = $id");
        $stmt->execute();
        http_response_code(200);
        echo json_encode(array('message' => 'Record updated successfully'));
        break;

    case 'DELETE':
        // Validate input data
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }

        // Sanitize input data
        $id = $pdo->quote($input['id']);

        // Delete existing record
        $stmt = $pdo->prepare("DELETE FROM $table_name WHERE id = $id");
        $stmt->execute();
        http_response_code(200);
        echo json_encode(array('message' => 'Record deleted successfully'));
        break;

    default:
        http_response_code(405);
        echo json_encode(array('error' => 'Method not allowed'));
        break;
}

// Close PDO connection
$pdo = null;

?>