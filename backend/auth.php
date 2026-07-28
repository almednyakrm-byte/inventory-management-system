<?php
// Start the session to handle user authentication
session_start();

// Include the database connection file
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, return a JSON response with their user data
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    echo json_encode($user);
    exit;
}

// Handle the login request
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    // Check if the username and password are set
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Sanitize the input fields
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Prepare the SQL query to check the username and password
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Check if the user exists and the password is correct
        if ($user && password_verify($password, $user['password'])) {
            // If the password is correct, set the user's session data
            $_SESSION['user_id'] = $user['id'];
            echo json_encode(array('success' => true, 'message' => 'Login successful'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Invalid username or password'));
        }
    } else {
        echo json_encode(array('success' => false, 'message' => 'Username and password are required'));
    }
}

// Handle the registration request
if (isset($_POST['action']) && $_POST['action'] == 'register') {
    // Check if the username, email, and password are set
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        // Sanitize the input fields
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Check if the username and email are unique
        $query = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo json_encode(array('success' => false, 'message' => 'Username or email already exists'));
            exit;
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query to insert the new user
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        $stmt->execute();

        // Set the user's session data
        $_SESSION['user_id'] = $mysqli->insert_id;
        echo json_encode(array('success' => true, 'message' => 'Registration successful'));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Username, email, and password are required'));
    }
}

// Handle the logout request
if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    // Destroy the session
    session_destroy();
    echo json_encode(array('success' => true, 'message' => 'Logout successful'));
}

// Close the database connection
$mysqli->close();
?>