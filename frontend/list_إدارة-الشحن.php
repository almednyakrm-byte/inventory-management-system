**list_إدارة-الشحن.php**

<?php
// Session validation
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
    <title>إدارة الشحن</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1f1f1f;
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
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #1f1f1f;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            font-size: 1.5rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar:focus {
            outline: none;
            border-color: #aaa;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-indigo-500 font-bold">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-red-500">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">إدارة الشحن</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_إدارة-الشحن.php'">إضافة عنصر جديد</button>
        <div class="flex justify-between items-center mb-4">
            <input type="search" class="search-bar" id="search-input" placeholder="بحث...">
            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>عنوان</th>
                    <th>وصف</th>
                    <th>حذف</th>
                    <th>تعديل</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch API to load records
        async function loadRecords() {
            const response = await fetch('../backend/إدارة-الشحن.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            const data = await response.json();
            const recordsTable = document.getElementById('records-table');
            recordsTable.innerHTML = '';
            data.forEach(record => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.title}</td>
                    <td>${record.description}</td>
                    <td><button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button></td>
                    <td><a href="edit_إدارة-الشحن.php?id=${record.id}" class="text-indigo-500 font-bold">تعديل</a></td>
                `;
                recordsTable.appendChild(row);
            });
        }

        // Search records
        function searchRecords() {
            const searchInput = document.getElementById('search-input').value;
            const recordsTable = document.getElementById('records-table');
            recordsTable.innerHTML = '';
            fetch('../backend/إدارة-الشحن.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                },
                params: {
                    search: searchInput
                }
            })
            .then(response => response.json())
            .then(data => {
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.title}</td>
                        <td>${record.description}</td>
                        <td><button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button></td>
                        <td><a href="edit_إدارة-شحن.php?id=${record.id}" class="text-indigo-500 font-bold">تعديل</a></td>
                    `;
                    recordsTable.appendChild(row);
                });
            });
        }

        // Delete record
        async function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا العنصر؟')) {
                const response = await fetch('../backend/إدارة-الشحن.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                });
                if (response.ok) {
                    loadRecords();
                } else {
                    alert('حدث خطأ أثناء الحذف');
                }
            }
        }

        // Load records on page load
        loadRecords();
    </script>
</body>
</html>

**backend/إدارة-الشحن.php**

<?php
// Get records from database
$records = array(
    array('id' => 1, 'title' => 'عنوان 1', 'description' => 'وصف 1'),
    array('id' => 2, 'title' => 'عنوان 2', 'description' => 'وصف 2'),
    array('id' => 3, 'title' => 'عنوان 3', 'description' => 'وصف 3')
);

// Search records
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $records = array_filter($records, function($record) use ($search) {
        return strpos($record['title'], $search) !== false || strpos($record['description'], $search) !== false;
    });
}

// Delete record
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $records = array_filter($records, function($record) use ($id) {
        return $record['id'] !== $id;
    });
}

// Output records in JSON format
header('Content-Type: application/json');
echo json_encode($records);
?>