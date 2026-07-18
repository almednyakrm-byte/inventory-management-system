<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define allowed roles for each operation
$allowedRoles = array(
    'GET' => array('admin', 'user'),
    'POST' => array('admin'),
    'PUT' => array('admin'),
    'DELETE' => array('admin')
);

// Check if user has permission to perform the requested operation
if (!in_array($_SESSION['user_role'], $allowedRoles[$_SERVER['REQUEST_METHOD']])) {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Validate input data
if (isset($input['id'])) {
    $input['id'] = (int) $input['id'];
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
    exit;
}

// Sanitize input data
$input = array_map('trim', $input);

// Connect to database
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $db->prepare('SELECT * FROM مخازن WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        http_response_code(200);
        echo json_encode($result);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input data
    if (!isset($input['name']) || !isset($input['address'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $input['name'] = trim($input['name']);
    $input['address'] = trim($input['address']);

    // Insert data into database
    $stmt = $db->prepare('INSERT INTO مخازن (name, address) VALUES (:name, :address)');
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':address', $input['address']);
    $stmt->execute();

    // Get the ID of the newly inserted record
    $id = $db->lastInsertId();

    // Return the inserted record
    http_response_code(201);
    echo json_encode(array('id' => $id, 'name' => $input['name'], 'address' => $input['address']));
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate input data
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['address'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $input['name'] = trim($input['name']);
    $input['address'] = trim($input['address']);

    // Update data in database
    $stmt = $db->prepare('UPDATE مخازن SET name = :name, address = :address WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':address', $input['address']);
    $stmt->execute();

    // Return the updated record
    http_response_code(200);
    echo json_encode(array('id' => $input['id'], 'name' => $input['name'], 'address' => $input['address']));
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate input data
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Delete data from database
    $stmt = $db->prepare('DELETE FROM مخازن WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();

    // Return success message
    http_response_code(204);
    echo json_encode(array('message' => 'Deleted successfully'));
}

// Close database connection
$db = null;



// Set headers
header('Content-Type: application/json');