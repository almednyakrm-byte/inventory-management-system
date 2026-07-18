**list_inventory.php**

<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2d3748;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            width: 80%;
            margin: 2rem auto;
        }
        .table th, .table td {
            padding: 1rem;
            border-bottom: 1px solid #ddd;
        }
        .table th {
            background-color: #2d3748;
            color: #fff;
        }
        .table td {
            color: #333;
        }
        .table td a {
            color: #337ab7;
            text-decoration: none;
        }
        .table td a:hover {
            color: #23527c;
        }
        .search-bar {
            width: 80%;
            margin: 2rem auto;
        }
        .search-bar input[type="search"] {
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
            width: 100%;
        }
        .search-bar button[type="submit"] {
            background-color: #2d3748;
            color: #fff;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .search-bar button[type="submit"]:hover {
            background-color: #3b4157;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">Back to Index</a>
        <span class="text-lg font-bold text-slate-900">Welcome, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-lg font-bold text-indigo-500">Logout</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-slate-900">Inventory Management</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_inventory.php'">Add New Item</button>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="Search...">
            <button type="submit" id="search-button">Search</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="inventory-table">
                <!-- Table data will be populated here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search-input');
        const searchButton = document.getElementById('search-button');
        const inventoryTable = document.getElementById('inventory-table');

        searchButton.addEventListener('click', async () => {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                const response = await fetch(`../backend/inventory.php?search=${searchQuery}`);
                const data = await response.json();
                populateTable(data);
            } else {
                const response = await fetch('../backend/inventory.php');
                const data = await response.json();
                populateTable(data);
            }
        });

        async function populateTable(data) {
            inventoryTable.innerHTML = '';
            data.forEach((item) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.id}</td>
                    <td>${item.name}</td>
                    <td>${item.quantity}</td>
                    <td>
                        <a href="edit_inventory.php?id=${item.id}" class="text-lg font-bold text-indigo-500">Edit</a>
                        <button class="text-lg font-bold text-red-500" onclick="deleteItem(${item.id})">Delete</button>
                    </td>
                `;
                inventoryTable.appendChild(row);
            });
        }

        async function deleteItem(id) {
            if (confirm('Are you sure you want to delete this item?')) {
                const response = await fetch(`../backend/inventory.php?delete=${id}`, { method: 'DELETE' });
                if (response.ok) {
                    populateTable(await fetch('../backend/inventory.php').then(response => response.json()));
                } else {
                    alert('Error deleting item');
                }
            }
        }
    </script>
</body>
</html>


**Note:** This code assumes that you have a `backend/inventory.php` file that handles GET and DELETE requests for inventory data. The `inventory.php` file should return a JSON response with the inventory data. The `delete_inventory.php` file should handle the DELETE request and return a success message if the item is deleted successfully.