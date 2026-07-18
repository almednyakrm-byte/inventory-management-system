<?php

require_once 'db.php';

// Get user role from session
$userRole = $_SESSION['userRole'];

// Check if user is logged in
if (!isset($_SESSION['loggedIn'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get all stock management data
    $stmt = $pdo->prepare('SELECT * FROM stock_management');
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return data in JSON format
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get input data from JSON
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($inputData['product_name']) || !isset($inputData['quantity'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $productName = filter_var($inputData['product_name'], FILTER_SANITIZE_STRING);
    $quantity = filter_var($inputData['quantity'], FILTER_SANITIZE_NUMBER_INT);

    // Insert new stock management data
    $stmt = $pdo->prepare('INSERT INTO stock_management (product_name, quantity) VALUES (:product_name, :quantity)');
    $stmt->bindParam(':product_name', $productName);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->execute();

    // Return success message
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Stock management data created successfully'));
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get input data from JSON
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($inputData['id']) || !isset($inputData['product_name']) || !isset($inputData['quantity'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);
    $productName = filter_var($inputData['product_name'], FILTER_SANITIZE_STRING);
    $quantity = filter_var($inputData['quantity'], FILTER_SANITIZE_NUMBER_INT);

    // Update existing stock management data
    $stmt = $pdo->prepare('UPDATE stock_management SET product_name = :product_name, quantity = :quantity WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':product_name', $productName);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Stock management data updated successfully'));
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get input data from JSON
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($inputData['id'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete stock management data
    $stmt = $pdo->prepare('DELETE FROM stock_management WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Stock management data deleted successfully'));
    exit;
}

// Return error message for invalid request method
http_response_code(405);
header('Content-Type: application/json');
echo json_encode(array('error' => 'Method not allowed'));
exit;