<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if user is admin
if (isset($_SESSION['role']) && $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Validate input data
if (empty($input)) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
    exit;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Sanitize query parameters
    $params = array_filter($input, 'filter_var');
    $params = array_map('filter_var', $params, FILTER_SANITIZE_STRING);

    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('SELECT * FROM products WHERE 1=1');
        foreach ($params as $key => $value) {
            $stmt->bindValue(':'.$key, $value);
        }
        $stmt->execute();

        // Fetch results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Output results
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($results);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle POST request
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input data
    if (!isset($input['name']) || !isset($input['description']) || !isset($input['price'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $input['name'] = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $input['description'] = filter_var($input['description'], FILTER_SANITIZE_STRING);
    $input['price'] = filter_var($input['price'], FILTER_SANITIZE_NUMBER_FLOAT);

    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('INSERT INTO products (name, description, price) VALUES (:name, :description, :price)');
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':description', $input['description']);
        $stmt->bindParam(':price', $input['price']);
        $stmt->execute();

        // Output result
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Product created successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle PUT request
elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Validate input data
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['description']) || !isset($input['price'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $input['id'] = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
    $input['name'] = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $input['description'] = filter_var($input['description'], FILTER_SANITIZE_STRING);
    $input['price'] = filter_var($input['price'], FILTER_SANITIZE_NUMBER_FLOAT);

    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('UPDATE products SET name = :name, description = :description, price = :price WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':description', $input['description']);
        $stmt->bindParam(':price', $input['price']);
        $stmt->execute();

        // Output result
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Product updated successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Validate input data
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $input['id'] = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);

    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->execute();

        // Output result
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Product deleted successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Output error message for invalid request method
else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
}