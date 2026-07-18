<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Define allowed roles for CRUD operations
$allowedRoles = [
    'GET' => ['user'],
    'POST' => ['user'],
    'PUT' => ['admin'],
    'DELETE' => ['admin']
];

// Define validation rules for input data
$validationRules = [
    'GET' => ['id' => 'integer'],
    'POST' => [
        'name' => 'string',
        'description' => 'string',
        'status' => 'integer'
    ],
    'PUT' => [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'status' => 'integer'
    ],
    'DELETE' => ['id' => 'integer']
];

// Validate input data
$isValid = true;
foreach ($validationRules[$_SERVER['REQUEST_METHOD']] as $field => $rule) {
    if (!isset($inputData[$field]) || !validate($inputData[$field], $rule)) {
        $isValid = false;
        break;
    }
}

// Sanitize input data
if ($isValid) {
    $sanitizedData = [];
    foreach ($inputData as $field => $value) {
        $sanitizedData[$field] = sanitize($value);
    }
    $inputData = $sanitizedData;
}

// Check user role
if (!in_array($_SESSION['role'], $allowedRoles[$_SERVER['REQUEST_METHOD']])) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

// Handle CRUD operations
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Get all shipments
        $query = "SELECT * FROM shipments";
        break;
    case 'POST':
        // Insert new shipment
        $query = "INSERT INTO shipments (name, description, status) VALUES (:name, :description, :status)";
        break;
    case 'PUT':
        // Update existing shipment
        $query = "UPDATE shipments SET name = :name, description = :description, status = :status WHERE id = :id";
        break;
    case 'DELETE':
        // Delete shipment
        $query = "DELETE FROM shipments WHERE id = :id";
        break;
}

// Prepare and execute SQL query
$stmt = $pdo->prepare($query);
if ($stmt->execute($inputData)) {
    // Process output
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            // Get all shipments
            $output = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($output);
            break;
        case 'POST':
            // Get inserted shipment ID
            $output = $pdo->lastInsertId();
            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode(['id' => $output]);
            break;
        case 'PUT':
            // Get updated shipment ID
            $output = $inputData['id'];
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['id' => $output]);
            break;
        case 'DELETE':
            // Get deleted shipment ID
            $output = $inputData['id'];
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['id' => $output]);
            break;
    }
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Internal Server Error']);
}

// Helper functions
function validate($value, $rule) {
    switch ($rule) {
        case 'integer':
            return is_numeric($value) && is_int((int) $value);
        case 'string':
            return is_string($value);
        default:
            return true;
    }
}

function sanitize($value) {
    return trim($value);
}
?>