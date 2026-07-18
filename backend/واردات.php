<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized access'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define table name
$table_name = 'واردات';

// Define validation rules
$validation_rules = array(
    'id' => 'integer',
    'name' => 'string',
    'description' => 'string',
    'quantity' => 'integer',
    'price' => 'float',
);

// Validate input data
foreach ($validation_rules as $field => $type) {
    if (isset($input[$field])) {
        switch ($type) {
            case 'integer':
                if (!is_int($input[$field])) {
                    http_response_code(400);
                    echo json_encode(array('error' => 'Invalid input: ' . $field));
                    exit;
                }
                break;
            case 'string':
                if (!is_string($input[$field])) {
                    http_response_code(400);
                    echo json_encode(array('error' => 'Invalid input: ' . $field));
                    exit;
                }
                break;
            case 'float':
                if (!is_float($input[$field])) {
                    http_response_code(400);
                    echo json_encode(array('error' => 'Invalid input: ' . $field));
                    exit;
                }
                break;
        }
    }
}

// Check if user is admin for edits/deletions
if (isset($input['id']) && $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden access'));
    exit;
}

// Handle CRUD operations
if (isset($input['id'])) {
    // Update operation
    $stmt = $pdo->prepare("UPDATE $table_name SET name = :name, description = :description, quantity = :quantity, price = :price WHERE id = :id");
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->bindParam(':quantity', $input['quantity']);
    $stmt->bindParam(':price', $input['price']);
    $stmt->execute();
    http_response_code(200);
    echo json_encode(array('message' => 'Updated successfully'));
} elseif (isset($input['name']) && isset($input['description']) && isset($input['quantity']) && isset($input['price'])) {
    // Insert operation
    $stmt = $pdo->prepare("INSERT INTO $table_name (name, description, quantity, price) VALUES (:name, :description, :quantity, :price)");
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->bindParam(':quantity', $input['quantity']);
    $stmt->bindParam(':price', $input['price']);
    $stmt->execute();
    http_response_code(201);
    echo json_encode(array('message' => 'Created successfully'));
} elseif (isset($input['id']) && $_SESSION['role'] == 'admin') {
    // Delete operation
    $stmt = $pdo->prepare("DELETE FROM $table_name WHERE id = :id");
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();
    http_response_code(200);
    echo json_encode(array('message' => 'Deleted successfully'));
} else {
    // Get all records operation
    $stmt = $pdo->prepare("SELECT * FROM $table_name");
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    echo json_encode($records);
}



// Add this at the top of your file
header('Content-Type: application/json');