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
    'GET' => 'user',
    'POST' => 'user',
    'PUT' => 'admin',
    'DELETE' => 'admin'
);

// Check if user has required role
if ($input['action'] && $allowedRoles[$input['action']] != $_SESSION['user_role']) {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Process request
switch ($input['action']) {
    case 'GET':
        // Retrieve all requests
        $stmt = $pdo->prepare('SELECT * FROM طلبات');
        $stmt->execute();
        $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($requests);
        break;

    case 'POST':
        // Validate and sanitize input data
        if (!isset($input['title']) || !isset($input['description'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }
        $title = filter_var($input['title'], FILTER_SANITIZE_STRING);
        $description = filter_var($input['description'], FILTER_SANITIZE_STRING);

        // Insert new request
        $stmt = $pdo->prepare('INSERT INTO طلبات (title, description) VALUES (:title, :description)');
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        http_response_code(201);
        echo json_encode(array('message' => 'Request created successfully'));
        break;

    case 'PUT':
        // Validate and sanitize input data
        if (!isset($input['id']) || !isset($input['title']) || !isset($input['description'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }
        $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
        $title = filter_var($input['title'], FILTER_SANITIZE_STRING);
        $description = filter_var($input['description'], FILTER_SANITIZE_STRING);

        // Update existing request
        $stmt = $pdo->prepare('UPDATE طلبات SET title = :title, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        http_response_code(200);
        echo json_encode(array('message' => 'Request updated successfully'));
        break;

    case 'DELETE':
        // Validate and sanitize input data
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }
        $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);

        // Delete request
        $stmt = $pdo->prepare('DELETE FROM طلبات WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        http_response_code(200);
        echo json_encode(array('message' => 'Request deleted successfully'));
        break;

    default:
        http_response_code(405);
        echo json_encode(array('error' => 'Method not allowed'));
        break;
}