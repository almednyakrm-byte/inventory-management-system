<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-900 text-white">
    <header class="bg-indigo-500 p-4 flex justify-between">
        <a href="index.php" class="text-lg font-bold">Back to Index</a>
        <div class="flex items-center">
            <span class="mr-4">Welcome, <?php echo $_SESSION['username']; ?></span>
            <a href="logout.php" class="bg-indigo-700 hover:bg-indigo-800 px-4 py-2 rounded">Logout</a>
        </div>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">Products List</h1>
        <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700" placeholder="Search products...">
        <table id="products-table" class="w-full mt-4">
            <thead class="bg-indigo-500">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Description</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="products-tbody">
                <!-- Table content will be populated via AJAX -->
            </tbody>
        </table>
        <a href="create_products.php" class="bg-indigo-700 hover:bg-indigo-800 px-4 py-2 rounded mt-4">Add New Item</a>
    </main>

    <script>
        // Fetch products data from backend
        fetch('../backend/products.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('products-tbody');
                data.forEach(product => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${product.id}</td>
                        <td>${product.name}</td>
                        <td>${product.description}</td>
                        <td>
                            <a href="edit_products.php?id=${product.id}" class="text-indigo-500 hover:text-indigo-800">Edit</a>
                            <button class="text-red-500 hover:text-red-800 ml-2" onclick="deleteProduct(${product.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            });

        // Delete product via AJAX
        function deleteProduct(id) {
            fetch('../backend/products.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the deleted product from the table
                    const rows = document.getElementById('products-tbody').children;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].children[0].textContent == id) {
                            rows[i].remove();
                            break;
                        }
                    }
                } else {
                    console.error('Error deleting product:', data.error);
                }
            });
        }

        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const filter = searchInput.value.toLowerCase();
            const rows = document.getElementById('products-tbody').children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const name = row.children[1].textContent.toLowerCase();
                const description = row.children[2].textContent.toLowerCase();
                if (name.includes(filter) || description.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>