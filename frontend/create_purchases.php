<?php
// create_purchases.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

include_once '../config.php';

$mod_slug = 'purchases';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Purchases</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-5xl mx-auto p-4 sm:p-6 md:p-8 bg-white rounded-xl shadow-md mt-10">
        <h2 class="text-3xl text-slate-900 font-bold mb-4">Create Purchases</h2>
        <form id="create-purchases-form">
            <div class="mb-4">
                <label for="date" class="block text-sm font-medium text-slate-900">Date</label>
                <input type="date" id="date" name="date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="supplier" class="block text-sm font-medium text-slate-900">Supplier</label>
                <input type="text" id="supplier" name="supplier" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="product" class="block text-sm font-medium text-slate-900">Product</label>
                <input type="text" id="product" name="product" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="quantity" class="block text-sm font-medium text-slate-900">Quantity</label>
                <input type="number" id="quantity" name="quantity" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="unit_price" class="block text-sm font-medium text-slate-900">Unit Price</label>
                <input type="number" id="unit_price" name="unit_price" step="0.01" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="total_cost" class="block text-sm font-medium text-slate-900">Total Cost</label>
                <input type="number" id="total_cost" name="total_cost" step="0.01" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <button type="submit" class="py-2 px-4 bg-indigo-500 text-white rounded-md hover:bg-indigo-700">Create Purchases</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-purchases-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/purchases.php',
                    data: $(this).serialize(),
                    success: function() {
                        window.location.href = 'list_purchases.php';
                    }
                });
            });
        });
    </script>
</body>
</html>