**list_الحركة.php**

<?php
session_start();

// Validate session
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
    <title>الحركة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
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
            background-color: #fff;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.25rem;
        }
        .table th, .table td {
            padding: 0.5rem;
            border-bottom: 1px solid #ddd;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .btn {
            background-color: #1a1d23;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #2c3e50;
        }
        .search {
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">Back to Index</a>
        <span class="text-lg font-bold">Welcome, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-lg font-bold text-red-500">Logout</a>
    </div>
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <h2 class="text-lg font-bold">الحركة</h2>
            <a href="create_الحركة.php" class="btn">Add New Item</a>
        </div>
        <div class="search relative">
            <input type="search" id="search" class="w-full py-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Search...">
            <button class="absolute top-0 right-0 py-2 px-4 text-sm font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-l" id="search-btn">Search</button>
        </div>
        <table class="table w-full">
            <thead>
                <tr>
                    <th class="text-left">ID</th>
                    <th class="text-left">Name</th>
                    <th class="text-left">Actions</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const searchBtn = document.getElementById('search-btn');
        const recordsTable = document.getElementById('records');

        searchBtn.addEventListener('click', () => {
            const searchQuery = searchInput.value.trim();
            fetch('../backend/الحركة.php?search=' + searchQuery)
                .then(response => response.json())
                .then(data => {
                    const recordsHtml = data.map(record => `
                        <tr>
                            <td>${record.id}</td>
                            <td>${record.name}</td>
                            <td>
                                <a href="edit_الحركة.php?id=${record.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                                <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">Delete</button>
                            </td>
                        </tr>
                    `).join('');
                    recordsTable.innerHTML = recordsHtml;
                });
        });

        function deleteRecord(id) {
            if (confirm('Are you sure you want to delete this record?')) {
                fetch('../backend/الحركة.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Record deleted successfully!');
                        window.location.reload();
                    } else {
                        alert('Error deleting record!');
                    }
                });
            }
        }

        fetch('../backend/الحركة.php')
            .then(response => response.json())
            .then(data => {
                const recordsHtml = data.map(record => `
                    <tr>
                        <td>${record.id}</td>
                        <td>${record.name}</td>
                        <td>
                            <a href="edit_الحركة.php?id=${record.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">Delete</button>
                        </td>
                    </tr>
                `).join('');
                recordsTable.innerHTML = recordsHtml;
            });
    </script>
</body>
</html>


**backend/الحركة.php**

<?php
// Database connection code here
// ...

if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $records = array_filter($records, function($record) use ($searchQuery) {
        return strpos($record['name'], $searchQuery) !== false;
    });
} else {
    $records = $db->query('SELECT * FROM الحركة')->fetchAll();
}

echo json_encode($records);


Note: This code assumes you have a database connection established and a table named `الحركة` with columns `id` and `name`. You'll need to modify the code to fit your specific database schema and backend logic.