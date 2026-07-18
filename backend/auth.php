<?php
// Start the session to handle user authentication
session_start();

// Import the database connection file
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, send a JSON response with their details
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $response = array('status' => 'logged_in', 'user_id' => $user_id, 'username' => $username);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Check for AJAX requests
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    // Handle login request
    if (isset($_POST['action']) && $_POST['action'] == 'login') {
        // Check if the username and password are set
        if (isset($_POST['username']) && isset($_POST['password'])) {
            // Sanitize the input fields
            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

            // Prepare the SQL query to select the user
            $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            // Fetch the user data
            $user = $stmt->fetch();

            // Check if the user exists and the password is correct
            if ($user && password_verify($password, $user['password'])) {
                // If the user is valid, log them in and send a JSON response
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $response = array('status' => 'logged_in');
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            } else {
                // If the user is not valid, send an error response
                $response = array('status' => 'invalid_credentials');
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
        } else {
            // If the username or password is missing, send an error response
            $response = array('status' => 'missing_credentials');
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    }

    // Handle register request
    elseif (isset($_POST['action']) && $_POST['action'] == 'register') {
        // Check if the username, email, and password are set
        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
            // Sanitize the input fields
            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

            // Check if the username and email are valid
            if (preg_match('/^[a-zA-Z0-9]+$/', $username) && preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
                // Prepare the SQL query to insert the new user
                $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', password_hash($password, PASSWORD_DEFAULT));
                $stmt->execute();

                // If the user is created, send a JSON response
                $response = array('status' => 'registered');
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            } else {
                // If the username or email is invalid, send an error response
                $response = array('status' => 'invalid_credentials');
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
        } else {
            // If the username, email, or password is missing, send an error response
            $response = array('status' => 'missing_credentials');
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    }

    // Handle logout request
    elseif (isset($_POST['action']) && $_POST['action'] == 'logout') {
        // Destroy the session and send a JSON response
        session_destroy();
        $response = array('status' => 'logged_out');
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}
?>


This code handles the following:

*   Checks if the user is already logged in and sends a JSON response with their details.
*   Handles AJAX requests for login, register, and logout.
*   Sanitizes input fields using `filter_var()` and `FILTER_SANITIZE_STRING`.
*   Uses prepared statements to prevent SQL injection.
*   Hashes passwords using `password_hash()` and verifies them using `password_verify()`.
*   Sends JSON responses for each action.
*   Destroys the session on logout.