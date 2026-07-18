**create_مخزون.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include connection file
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $quantity = trim($_POST['quantity']);
    $unit_price = trim($_POST['unit_price']);

    if (!empty($name) && !empty($quantity) && !empty($unit_price)) {
        // Insert data into database
        $sql = "INSERT INTO مخزون (name, quantity, unit_price) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $quantity, $unit_price);
        $stmt->execute();

        // Redirect to list page
        header('Location: list_مخزون.php');
        exit;
    } else {
        $error = 'Please fill in all fields';
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة مخزون جديد</title>
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
            color: #6b5f7e;
        }
    </style>
</head>
<body class="bg-slate-900 text-white">
    <div class="max-w-md mx-auto p-4 mt-4 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4">إضافة مخزون جديد</h2>
        <form id="create-form" method="post">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium mb-2">اسم المخزون:</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-300 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="اسم المخزون">
            </div>
            <div class="mb-4">
                <label for="quantity" class="block text-sm font-medium mb-2">الكمية:</label>
                <input type="number" id="quantity" name="quantity" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-300 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="الكمية">
            </div>
            <div class="mb-4">
                <label for="unit_price" class="block text-sm font-medium mb-2">سعر الوحدة:</label>
                <input type="number" id="unit_price" name="unit_price" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-300 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="سعر الوحدة">
            </div>
            <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة</button>
            <?php if (isset($error)) : ?>
                <p class="text-red-500 mt-2"><?= $error ?></p>
            <?php endif; ?>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: '../backend/مخزون.php',
                    data: formData,
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_مخزون.php';
                        } else {
                            alert('Error adding new stock');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

**Note:** Make sure to replace `../backend/مخزون.php` with the actual URL of your backend script that handles the form submission. Also, update the `list_مخزون.php` URL to match the actual URL of your list page.