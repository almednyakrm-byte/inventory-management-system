<?php

require_once 'db.php';

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Get the user role and ID from the session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Check if the user is logged in
if (!$userID) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Handle GET requests
if ($method === 'GET') {
    $id = $_GET['id'] ?? null;

    // Check if the user is an admin to allow edit and delete operations
    if ($userRole !== 'admin' && ($id || $id === 0)) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Select all orders
    if (!$id) {
        $stmt = $pdo->prepare('SELECT * FROM orders');
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($orders);
    }
    // Select a specific order
    else {
        $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$order) {
            http_response_code(404);
            echo json_encode(['error' => 'Not Found']);
        } else {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($order);
        }
    }
}

// Handle POST requests
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate the request data
    if (!$data || !isset($data['customer_name'], $data['order_date'], $data['total'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Bad Request']);
        exit;
    }

    // Sanitize the request data
    $customer_name = filter_var($data['customer_name'], FILTER_SANITIZE_STRING);
    $order_date = filter_var($data['order_date'], FILTER_SANITIZE_STRING);
    $total = filter_var($data['total'], FILTER_SANITIZE_NUMBER_INT);

    // Insert a new order
    $stmt = $pdo->prepare('INSERT INTO orders (customer_name, order_date, total) VALUES (:customer_name, :order_date, :total)');
    $stmt->bindParam(':customer_name', $customer_name);
    $stmt->bindParam(':order_date', $order_date);
    $stmt->bindParam(':total', $total);
    $stmt->execute();

    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Order created successfully']);
}

// Handle PUT requests
elseif ($method === 'PUT') {
    $id = $_GET['id'] ?? null;
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if the user is an admin to allow edit operations
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate the request data
    if (!$data || !isset($data['customer_name'], $data['order_date'], $data['total'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Bad Request']);
        exit;
    }

    // Sanitize the request data
    $customer_name = filter_var($data['customer_name'], FILTER_SANITIZE_STRING);
    $order_date = filter_var($data['order_date'], FILTER_SANITIZE_STRING);
    $total = filter_var($data['total'], FILTER_SANITIZE_NUMBER_INT);

    // Update an existing order
    $stmt = $pdo->prepare('UPDATE orders SET customer_name = :customer_name, order_date = :order_date, total = :total WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':customer_name', $customer_name);
    $stmt->bindParam(':order_date', $order_date);
    $stmt->bindParam(':total', $total);
    $stmt->execute();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Order updated successfully']);
}

// Handle DELETE requests
elseif ($method === 'DELETE') {
    $id = $_GET['id'] ?? null;

    // Check if the user is an admin to allow delete operations
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Delete an existing order
    $stmt = $pdo->prepare('DELETE FROM orders WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Order deleted successfully']);
}

// Handle invalid requests
else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}