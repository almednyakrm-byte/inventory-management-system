<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}

// Get current user info
$current_user = $_SESSION['user'];

// Include backend connection
require_once '../backend/connection.php';

// Get warehouses list from backend
$warehouses = json_decode(file_get_contents('../backend/warehouses.php'), true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouses List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-900 text-white">
    <header class="bg-indigo-500 py-4">
        <nav class="container mx-auto flex justify-between">
            <a href="index.php" class="text-lg font-bold">Home</a>
            <span class="text-lg font-bold">Welcome, <?= $current_user['name'] ?></span>
            <a href="logout.php" class="text-lg font-bold">Logout</a>
        </nav>
    </header>
    <main class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <h1 class="text-3xl font-bold mb-4">Warehouses List</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_warehouses.php'">Add New Item</button>
            <input type="text" id="search" class="bg-slate-800 text-white font-bold py-2 px-4 rounded" placeholder="Search...">
        </div>
        <table class="w-full table-auto" id="warehouses-table">
            <thead class="bg-slate-800">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="warehouses-tbody">
                <?php foreach ($warehouses as $warehouse) { ?>
                <tr>
                    <td class="px-4 py-2"><?= $warehouse['id'] ?></td>
                    <td class="px-4 py-2"><?= $warehouse['name'] ?></td>
                    <td class="px-4 py-2">
                        <a href="edit_warehouses.php?id=<?= $warehouse['id'] ?>" class="text-indigo-500 hover:text-indigo-700">Edit</a>
                        <button class="text-red-500 hover:text-red-700" onclick="deleteWarehouse(<?= $warehouse['id'] ?>)">Delete</button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>

    <script>
        // Fetch warehouses list from backend
        async function fetchWarehouses() {
            const response = await fetch('../backend/warehouses.php');
            const warehouses = await response.json();
            return warehouses;
        }

        // Delete warehouse
        async function deleteWarehouse(id) {
            const response = await fetch('../backend/warehouses.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            });
            const result = await response.json();
            if (result.success) {
                location.reload();
            } else {
                alert('Error deleting warehouse');
            }
        }

        // Search warehouses
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', async () => {
            const searchQuery = searchInput.value.toLowerCase();
            const warehouses = await fetchWarehouses();
            const filteredWarehouses = warehouses.filter(warehouse => {
                return warehouse.name.toLowerCase().includes(searchQuery);
            });
            const tbody = document.getElementById('warehouses-tbody');
            tbody.innerHTML = '';
            filteredWarehouses.forEach(warehouse => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-4 py-2">${warehouse.id}</td>
                    <td class="px-4 py-2">${warehouse.name}</td>
                    <td class="px-4 py-2">
                        <a href="edit_warehouses.php?id=${warehouse.id}" class="text-indigo-500 hover:text-indigo-700">Edit</a>
                        <button class="text-red-500 hover:text-red-700" onclick="deleteWarehouse(${warehouse.id})">Delete</button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        });
    </script>
</body>
</html>