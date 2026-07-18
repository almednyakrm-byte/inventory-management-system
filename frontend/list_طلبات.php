**list_طلبات.php**

<?php
// Session validation
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
    <title>طلبات</title>
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
            border-collapse: collapse;
            width: 100%;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #1a1d23;
            color: #fff;
        }
        .search-bar {
            padding: 1rem;
            background-color: #f7f7f7;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
        }
        .search-bar input {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">Back to Index</a>
        <span class="text-indigo-500">Welcome, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">Logout</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">طلبات</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_طلبات.php'">Add New Item</button>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="Search...">
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Item Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Table records will be populated here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search-input');
        const tableBody = document.getElementById('table-body');

        searchInput.addEventListener('input', () => {
            const searchQuery = searchInput.value.toLowerCase();
            const tableRows = tableBody.getElementsByTagName('tr');
            for (let i = 0; i < tableRows.length; i++) {
                const row = tableRows[i];
                const itemName = row.cells[1].textContent.toLowerCase();
                if (itemName.includes(searchQuery)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });

        async function fetchRecords() {
            try {
                const response = await fetch('../backend/طلبات.php', { method: 'GET' });
                const data = await response.json();
                const tableRows = document.createElement('tbody');
                data.forEach((record) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.id}</td>
                        <td>${record.item_name}</td>
                        <td>
                            <a href="edit_طلبات.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">Delete</button>
                        </td>
                    `;
                    tableRows.appendChild(row);
                });
                tableBody.innerHTML = '';
                tableBody.appendChild(tableRows);
            } catch (error) {
                console.error(error);
            }
        }

        async function deleteRecord(id) {
            try {
                const response = await fetch('../backend/طلبات.php', { method: 'DELETE', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id }) });
                if (response.ok) {
                    fetchRecords();
                } else {
                    console.error('Error deleting record');
                }
            } catch (error) {
                console.error(error);
            }
        }

        fetchRecords();
    </script>
</body>
</html>

**backend/طلبات.php**

<?php
// Assuming a database connection is established
// Fetch all records from the database
$records = array(
    array('id' => 1, 'item_name' => 'Item 1'),
    array('id' => 2, 'item_name' => 'Item 2'),
    array('id' => 3, 'item_name' => 'Item 3'),
);

// If a DELETE request is made, delete the record with the specified ID
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_POST['id'];
    // Delete the record from the database
    // ...
    echo json_encode(array('message' => 'Record deleted successfully'));
    exit;
}

// If a GET request is made, return all records
echo json_encode($records);