**create_inventory.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include navigation
include 'navigation.php';

// Include form
include 'create_inventory_form.php';

// Include footer
include 'footer.php';


**create_inventory_form.php**

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24 2xl:px-48">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-slate-900 font-bold text-lg mb-4">Create New Inventory</h2>
        <form id="create-inventory-form">
            <div class="mb-4">
                <label for="name" class="text-slate-900 font-bold">Name:</label>
                <input type="text" id="name" name="name" class="w-full p-2 mb-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="description" class="text-slate-900 font-bold">Description:</label>
                <textarea id="description" name="description" class="w-full p-2 mb-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
            </div>
            <div class="mb-4">
                <label for="quantity" class="text-slate-900 font-bold">Quantity:</label>
                <input type="number" id="quantity" name="quantity" class="w-full p-2 mb-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="category" class="text-slate-900 font-bold">Category:</label>
                <select id="category" name="category" class="w-full p-2 mb-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                    <option value="">Select a category</option>
                    <option value="Electronics">Electronics</option>
                    <option value="Fashion">Fashion</option>
                    <option value="Home Goods">Home Goods</option>
                </select>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Create Inventory</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-inventory-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/inventory.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_inventory.php';
                    } else {
                        alert('Error creating inventory');
                    }
                }
            });
        });
    });
</script>


**header.php** (basic header)

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Inventory</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>


**footer.php** (basic footer)

</body>
</html>


**navigation.php** (basic navigation)

<nav class="bg-white shadow-md p-4">
    <ul class="flex justify-between items-center">
        <li><a href="#" class="text-slate-900 font-bold">Inventory</a></li>
        <li><a href="#" class="text-slate-900 font-bold">Settings</a></li>
        <li><a href="#" class="text-slate-900 font-bold">Logout</a></li>
    </ul>
</nav>


Note: This code assumes you have jQuery and Tailwind CSS installed. You'll need to modify the `backend/inventory.php` file to handle the form data and return a success message.