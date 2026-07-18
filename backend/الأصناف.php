<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get user role
$userRole = $_SESSION['user_role'];

// Check if user is admin
$isAdmin = ($userRole == 'admin');

// Read inputs from JSON body
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Validate input parameters
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing id parameter'));
        exit;
    }

    // Sanitize input parameters
    $id = intval($input['id']);

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Fetch result
    $category = $stmt->fetch();

    // Check if category exists
    if (!$category) {
        http_response_code(404);
        echo json_encode(array('error' => 'Category not found'));
        exit;
    }

    // Output result
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($category);
}

// Handle POST request
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input parameters
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing name or description parameter'));
        exit;
    }

    // Sanitize input parameters
    $name = trim($input['name']);
    $description = trim($input['description']);

    // Prepare SQL query
    $stmt = $pdo->prepare('INSERT INTO categories (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Get last inserted ID
    $id = $pdo->lastInsertId();

    // Output result
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $id));
}

// Handle PUT request
elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Validate input parameters
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing id, name, or description parameter'));
        exit;
    }

    // Sanitize input parameters
    $id = intval($input['id']);
    $name = trim($input['name']);
    $description = trim($input['description']);

    // Check if user is admin
    if (!$isAdmin) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('UPDATE categories SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Check if category exists
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $category = $stmt->fetch();

    // Check if category exists
    if (!$category) {
        http_response_code(404);
        echo json_encode(array('error' => 'Category not found'));
        exit;
    }

    // Output result
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($category);
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Validate input parameters
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing id parameter'));
        exit;
    }

    // Sanitize input parameters
    $id = intval($input['id']);

    // Check if user is admin
    if (!$isAdmin) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('DELETE FROM categories WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Check if category exists
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $category = $stmt->fetch();

    // Check if category exists
    if ($category) {
        http_response_code(400);
        echo json_encode(array('error' => 'Category not found'));
        exit;
    }

    // Output result
    http_response_code(204);
    header('Content-Type: application/json');
    echo json_encode(array());
}