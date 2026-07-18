<?php
// create_warehouses.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include_once '../config.php';

$mod_slug = 'warehouses';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Warehouse</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-5xl mx-auto p-4 sm:p-6 md:p-8 bg-white rounded shadow-md">
        <h2 class="text-lg font-medium text-slate-900">Create Warehouse</h2>
        <form id="create-warehouse-form">
            <div class="grid grid-cols-1 gap-4 mt-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
                    <input type="text" id="name" name="name" class="block w-full mt-1 text-sm text-slate-900 border border-slate-200 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="address" class="block text-sm font-medium text-slate-900">Address</label>
                    <input type="text" id="address" name="address" class="block w-full mt-1 text-sm text-slate-900 border border-slate-200 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="city" class="block text-sm font-medium text-slate-900">City</label>
                    <input type="text" id="city" name="city" class="block w-full mt-1 text-sm text-slate-900 border border-slate-200 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="state" class="block text-sm font-medium text-slate-900">State</label>
                    <input type="text" id="state" name="state" class="block w-full mt-1 text-sm text-slate-900 border border-slate-200 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="zip" class="block text-sm font-medium text-slate-900">Zip</label>
                    <input type="text" id="zip" name="zip" class="block w-full mt-1 text-sm text-slate-900 border border-slate-200 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="country" class="block text-sm font-medium text-slate-900">Country</label>
                    <input type="text" id="country" name="country" class="block w-full mt-1 text-sm text-slate-900 border border-slate-200 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-900">Phone</label>
                    <input type="text" id="phone" name="phone" class="block w-full mt-1 text-sm text-slate-900 border border-slate-200 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-900">Email</label>
                    <input type="email" id="email" name="email" class="block w-full mt-1 text-sm text-slate-900 border border-slate-200 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            <button type="submit" class="inline-flex justify-center py-2 px-4 mt-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create Warehouse</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-warehouse-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/warehouses.php',
                    data: $(this).serialize(),
                    success: function() {
                        window.location.href = 'list_warehouses.php';
                    }
                });
            });
        });
    </script>
</body>
</html>