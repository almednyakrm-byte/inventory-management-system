<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Initialize database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get inputs
$input = json_decode(file_get_contents('php://input'), true);
if (empty($input)) {
    $input = $_POST;
}

// GET method: Retrieve all materials
if ($method === 'GET') {
    // Validate and sanitize inputs
    $id = isset($input['id']) ? (int) $input['id'] : null;

    // Prepare SQL query
    $sql = 'SELECT * FROM مواد';
    $params = [];
    if ($id !== null) {
        $sql .= ' WHERE id = :id';
        $params[':id'] = $id;
    }

    // Execute query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Process output
    $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($materials);
}

// POST method: Create new material
elseif ($method === 'POST') {
    // Validate and sanitize inputs
    $name = isset($input['name']) ? trim($input['name']) : null;
    $description = isset($input['description']) ? trim($input['description']) : null;

    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Check if inputs are valid
    if (empty($name) || empty($description)) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // Prepare SQL query
    $sql = 'INSERT INTO مواد (name, description) VALUES (:name, :description)';
    $params = [
        ':name' => $name,
        ':description' => $description,
    ];

    // Execute query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Process output
    $materialId = $pdo->lastInsertId();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $materialId]);
}

// PUT method: Update existing material
elseif ($method === 'PUT') {
    // Validate and sanitize inputs
    $id = isset($input['id']) ? (int) $input['id'] : null;
    $name = isset($input['name']) ? trim($input['name']) : null;
    $description = isset($input['description']) ? trim($input['description']) : null;

    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Check if inputs are valid
    if (empty($id) || (empty($name) && empty($description))) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // Prepare SQL query
    $sql = 'UPDATE مواد SET ';
    $params = [];
    if (!empty($name)) {
        $sql .= 'name = :name';
        $params[':name'] = $name;
    }
    if (!empty($description)) {
        if (!empty($name)) {
            $sql .= ', ';
        }
        $sql .= 'description = :description';
        $params[':description'] = $description;
    }
    $sql .= ' WHERE id = :id';
    $params[':id'] = $id;

    // Execute query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Process output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Material updated successfully']);
}

// DELETE method: Delete existing material
elseif ($method === 'DELETE') {
    // Validate and sanitize inputs
    $id = isset($input['id']) ? (int) $input['id'] : null;

    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Check if input is valid
    if (empty($id)) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    // Prepare SQL query
    $sql = 'DELETE FROM مواد WHERE id = :id';
    $params = [
        ':id' => $id,
    ];

    // Execute query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Process output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Material deleted successfully']);
}

// Invalid method
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}