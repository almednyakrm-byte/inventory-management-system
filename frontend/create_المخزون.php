**create_المخزون.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $quantity = trim($_POST['quantity']);
    $description = trim($_POST['description']);

    if (empty($name) || empty($quantity) || empty($description)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $sql = "INSERT INTO المخزون (name, quantity, description) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sis', $name, $quantity, $description);
        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            // Redirect back to list page
            header('Location: list_المخزون.php');
            exit;
        } else {
            $error = 'Failed to create record';
        }
    }
}

// Include header and navigation
require_once '../includes/header.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12 2xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-8 2xl:p-8">
        <h2 class="text-slate-900 font-bold text-lg mb-4">Create New المخزون</h2>
        <form id="create-form" method="post" class="space-y-4">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label for="name" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">Name</label>
                    <input type="text" id="name" name="name" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="Enter name">
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label for="quantity" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">Quantity</label>
                    <input type="number" id="quantity" name="quantity" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="Enter quantity">
                </div>
            </div>
            <div class="w-full px-3 mb-6">
                <label for="description" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">Description</label>
                <textarea id="description" name="description" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="Enter description"></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create</button>
        </form>
        <?php if (isset($error)) : ?>
            <p class="text-red-500 text-xs mt-2"><?= $error ?></p>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>


**create_المخزون.js**
javascript
$(document).ready(function() {
    $('#create-form').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: '../backend/المخزون.php',
            data: formData,
            success: function(response) {
                if (response === 'success') {
                    window.location.href = 'list_المخزون.php';
                } else {
                    alert('Failed to create record');
                }
            }
        });
    });
});


**../backend/المخزون.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $quantity = trim($_POST['quantity']);
    $description = trim($_POST['description']);

    // Insert data into database
    $sql = "INSERT INTO المخزون (name, quantity, description) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sis', $name, $quantity, $description);
    $stmt->execute();

    if ($stmt->affected_rows === 1) {
        echo 'success';
    } else {
        echo 'Failed to create record';
    }
}