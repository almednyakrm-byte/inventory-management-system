<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get user role
$user_role = $_SESSION['user_role'];

// Get input data
$input_data = json_decode(file_get_contents('php://input'), true);

// Validate input data
if (empty($input_data)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

// Define table name
$table_name = 'متتابعات';

// GET all records
if (isset($_GET['action']) && $_GET['action'] == 'get_all') {
    try {
        // Prepare SQL query
        $stmt = $pdo->prepare("SELECT * FROM $table_name");
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($records);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
}

// GET single record
if (isset($_GET['action']) && $_GET['action'] == 'get_one') {
    try {
        // Prepare SQL query
        $stmt = $pdo->prepare("SELECT * FROM $table_name WHERE id = :id");
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($record) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Record not found']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
}

// POST new record
if (isset($_GET['action']) && $_GET['action'] == 'create') {
    try {
        // Validate input data
        if (!isset($input_data['name']) || !isset($input_data['email'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request']);
            exit;
        }

        // Sanitize input data
        $name = filter_var($input_data['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($input_data['email'], FILTER_SANITIZE_EMAIL);

        // Prepare SQL query
        $stmt = $pdo->prepare("INSERT INTO $table_name (name, email) VALUES (:name, :email)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Get inserted ID
        $id = $pdo->lastInsertId();

        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['id' => $id]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
}

// PUT update record
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    try {
        // Validate input data
        if (!isset($input_data['id']) || !isset($input_data['name']) || !isset($input_data['email'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request']);
            exit;
        }

        // Sanitize input data
        $id = filter_var($input_data['id'], FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var($input_data['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($input_data['email'], FILTER_SANITIZE_EMAIL);

        // Check user role
        if ($user_role != 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }

        // Prepare SQL query
        $stmt = $pdo->prepare("UPDATE $table_name SET name = :name, email = :email WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Record updated successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
}

// DELETE record
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    try {
        // Validate input data
        if (!isset($input_data['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request']);
            exit;
        }

        // Sanitize input data
        $id = filter_var($input_data['id'], FILTER_SANITIZE_NUMBER_INT);

        // Check user role
        if ($user_role != 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }

        // Prepare SQL query
        $stmt = $pdo->prepare("DELETE FROM $table_name WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Record deleted successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
}
?>