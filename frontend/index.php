<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة مخزونات ومخازن</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .glassmorphism {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-slate-900 text-white">
    <div class="container mx-auto p-4 pt-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold">نظام إدارة مخزونات ومخازن</h1>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
        </div>
        <div class="glassmorphism p-4 mb-4">
            <h2 class="text-2xl font-bold mb-2">معلومات عامة</h2>
            <p class="text-lg">مرحباً بكم في نظام إدارة مخزونات ومخازن</p>
        </div>
        <div class="glassmorphism p-4 mb-4">
            <h2 class="text-2xl font-bold mb-2">إحصائيات</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-slate-800 rounded p-4">
                    <h3 class="text-lg font-bold mb-2">مخازن</h3>
                    <p id="warehouses-count" class="text-lg"></p>
                </div>
                <div class="bg-slate-800 rounded p-4">
                    <h3 class="text-lg font-bold mb-2">مخزونات</h3>
                    <p id="stocks-count" class="text-lg"></p>
                </div>
                <div class="bg-slate-800 rounded p-4">
                    <h3 class="text-lg font-bold mb-2">فواتير</h3>
                    <p id="invoices-count" class="text-lg"></p>
                </div>
            </div>
        </div>
        <div class="glassmorphism p-4 mb-4">
            <h2 class="text-2xl font-bold mb-2">إدارة</h2>
            <div class="flex justify-between items-center">
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='warehouses.php'">مخازن</button>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='stocks.php'">مخزونات</button>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='invoices.php'">فواتير</button>
            </div>
        </div>
    </div>

    <script>
        fetch('api/stats.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('warehouses-count').innerText = data.warehouses_count;
                document.getElementById('stocks-count').innerText = data.stocks_count;
                document.getElementById('invoices-count').innerText = data.invoices_count;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


Note: This code assumes you have a PHP file named `api/stats.php` that returns a JSON object with the statistics data. You will need to create this file and implement the API endpoint to fetch the data.


<?php
header('Content-Type: application/json');

// Fetch data from database or other source
$warehouses_count = 10;
$stocks_count = 20;
$invoices_count = 30;

echo json_encode([
    'warehouses_count' => $warehouses_count,
    'stocks_count' => $stocks_count,
    'invoices_count' => $invoices_count
]);
?>


This is a basic example and you will need to modify it to fit your specific requirements.