**create_مخزونات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $quantity = trim($_POST['quantity']);
    $unit_price = trim($_POST['unit_price']);

    // Check if fields are not empty
    if (!empty($name) && !empty($quantity) && !empty($unit_price)) {
        // Insert data into database
        $query = "INSERT INTO مخزونات (name, quantity, unit_price) VALUES ('$name', '$quantity', '$unit_price')";
        $result = mysqli_query($conn, $query);

        // Check if data has been inserted successfully
        if ($result) {
            // Redirect back to list page
            header('Location: list_مخزونات.php');
            exit;
        } else {
            // Display error message
            $error = 'Error inserting data';
        }
    } else {
        // Display error message
        $error = 'Please fill in all fields';
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة مخزون</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .bg-slate-900 {
            background-color: #1a1a1a;
        }
        .text-indigo-500 {
            color: #6b7280;
        }
    </style>
</head>
<body class="bg-slate-900">
    <div class="container mx-auto p-4 md:p-6 lg:p-8">
        <h1 class="text-3xl text-indigo-500 font-bold mb-4">إضافة مخزون</h1>
        <form id="create-form" method="POST" action="" class="bg-white p-4 md:p-6 lg:p-8 shadow-md rounded-md">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">اسم المخزون:</label>
                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">الكمية:</label>
                <input type="number" id="quantity" name="quantity" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="unit_price" class="block text-gray-700 text-sm font-bold mb-2">سعر الوحدة:</label>
                <input type="number" id="unit_price" name="unit_price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/مخزونات.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response == 'success') {
                            window.location.href = 'list_مخزونات.php';
                        } else {
                            alert('Error adding data');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

**Note:** This code assumes that you have a database connection established in `db.php` file and a table named `مخزونات` with columns `name`, `quantity`, and `unit_price`. Also, this code uses jQuery for AJAX request. Make sure to include jQuery library in your HTML file.