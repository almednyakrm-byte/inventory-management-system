<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

// Validate input
if (!isset($input['id']) && !isset($input['title']) && !isset($input['description'])) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
    exit;
}

// Sanitize input
$input['title'] = trim($input['title'] ?? '');
$input['description'] = trim($input['description'] ?? '');

// Handle GET request
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM reports WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $report = $stmt->fetch();
    if (!$report) {
        http_response_code(404);
        echo json_encode(array('error' => 'Report not found'));
        exit;
    }
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($report);
    exit;
}

// Handle GET request for all reports
if (isset($_GET['page']) && isset($_GET['limit'])) {
    $page = (int) $_GET['page'];
    $limit = (int) $_GET['limit'];
    $offset = ($page - 1) * $limit;
    $stmt = $pdo->prepare('SELECT * FROM reports LIMIT :limit OFFSET :offset');
    $stmt->execute(['limit' => $limit, 'offset' => $offset]);
    $reports = $stmt->fetchAll();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($reports);
    exit;
}

// Handle POST request
if (isset($input['title']) && isset($input['description'])) {
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    $stmt = $pdo->prepare('INSERT INTO reports (title, description, user_id) VALUES (:title, :description, :user_id)');
    $stmt->execute(['title' => $input['title'], 'description' => $input['description'], 'user_id' => $_SESSION['user_id']]);
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Report created successfully'));
    exit;
}

// Handle PUT request
if (isset($input['id']) && isset($input['title']) && isset($input['description'])) {
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    $id = (int) $input['id'];
    $stmt = $pdo->prepare('UPDATE reports SET title = :title, description = :description WHERE id = :id');
    $stmt->execute(['title' => $input['title'], 'description' => $input['description'], 'id' => $id]);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Report updated successfully'));
    exit;
}

// Handle DELETE request
if (isset($input['id'])) {
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    $id = (int) $input['id'];
    $stmt = $pdo->prepare('DELETE FROM reports WHERE id = :id');
    $stmt->execute(['id' => $id]);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Report deleted successfully'));
    exit;
}

http_response_code(405);
echo json_encode(array('error' => 'Method not allowed'));
exit;