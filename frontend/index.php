<!-- index.php -->
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
    <title>نظام إدارة المخازن</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
        }
        .glassmorphism-card {
            background-color: #f0f2f5;
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
            padding: 10px;
            border-bottom: 1px solid #333;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }
        .stat-card {
            background-color: #f0f2f5;
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .quick-links {
            display: flex;
            gap: 10px;
        }
        .quick-link {
            background-color: #f0f2f5;
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="text-3xl text-center">نظام إدارة المخازن</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
    </div>
    <div class="glassmorphism-card">
        <h2 class="text-2xl text-center">مرحباً</h2>
        <p class="text-lg text-center">نظام إدارة المخازن</p>
    </div>
    <div class="glassmorphism-card mt-10">
        <h2 class="text-2xl text-center">إحصائيات</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <h3 class="text-lg text-center">إمدادات</h3>
                <p class="text-lg text-center" id="supplies-count"></p>
            </div>
            <div class="stat-card">
                <h3 class="text-lg text-center">إنتاج</h3>
                <p class="text-lg text-center" id="production-count"></p>
            </div>
            <div class="stat-card">
                <h3 class="text-lg text-center">مخزون</h3>
                <p class="text-lg text-center" id="inventory-count"></p>
            </div>
            <div class="stat-card">
                <h3 class="text-lg text-center">صفقات</h3>
                <p class="text-lg text-center" id="deals-count"></p>
            </div>
        </div>
    </div>
    <div class="glassmorphism-card mt-10">
        <h2 class="text-2xl text-center">روابط سريعة</h2>
        <div class="quick-links">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='supplies.php'">إمدادات</button>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='production.php'">إنتاج</button>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='inventory.php'">مخزون</button>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='deals.php'">صفقات</button>
        </div>
    </div>

    <script>
        // Fetch stats from backend API
        fetch('/api/stats')
            .then(response => response.json())
            .then(data => {
                document.getElementById('supplies-count').textContent = data.supplies_count;
                document.getElementById('production-count').textContent = data.production_count;
                document.getElementById('inventory-count').textContent = data.inventory_count;
                document.getElementById('deals-count').textContent = data.deals_count;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code assumes you have a backend API set up to fetch the stats data. The API endpoint is assumed to be `/api/stats` and it returns a JSON response with the stats data.

You will need to replace the `fetch` API call with your own API call to fetch the stats data.

Also, make sure to update the `logout.php` file to handle the logout functionality.

Note: This code uses Tailwind CSS for styling and it's assumed that you have it set up in your project. If not, you can add it by running `npm install tailwindcss` and then creating a `tailwind.config.js` file to configure it.