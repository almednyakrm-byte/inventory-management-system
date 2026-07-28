<?php

require_once 'db.php';

// Get user role and authentication status
$userRole = $_SESSION['userRole'];
$authStatus = $_SESSION['authStatus'];

// Check if user is logged in and authorized
if (!$authStatus || ($userRole != 'admin' && $_SERVER['REQUEST_METHOD'] == 'PUT' || $_SERVER['REQUEST_METHOD'] == 'DELETE')) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized access'));
    exit;
}

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Retrieve all records
    $sql = "SELECT * FROM مخزون";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input data
    if (!isset($inputData['name']) || !isset($inputData['quantity'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
    $quantity = filter_var($inputData['quantity'], FILTER_SANITIZE_NUMBER_INT);

    // Insert new record
    $sql = "INSERT INTO مخزون (name, quantity) VALUES (:name, :quantity)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':quantity', $quantity);
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array('message' => 'Record created successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to create record'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Validate input data
    if (!isset($inputData['id']) || !isset($inputData['name']) || !isset($inputData['quantity'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
    $quantity = filter_var($inputData['quantity'], FILTER_SANITIZE_NUMBER_INT);

    // Update existing record
    $sql = "UPDATE مخزون SET name = :name, quantity = :quantity WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':quantity', $quantity);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Record updated successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to update record'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Validate input data
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete existing record
    $sql = "DELETE FROM مخزون WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Record deleted successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to delete record'));
    }
}



<?php

// db.php

// PDO database connection settings
$dsn = 'mysql:host=localhost;dbname=database_name';
$username = 'database_username';
$password = 'database_password';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}