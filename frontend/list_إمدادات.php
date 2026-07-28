**list_إمدادات.php**

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
    <title>إمدادات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1f2937;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #ffffff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ffffff;
            text-decoration: underline;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: center;
        }
        .table th {
            background-color: #1f2937;
            color: #ffffff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold text-slate-900">إمدادات</span>
        <span class="text-lg font-bold text-indigo-500"><?= $_SESSION['username'] ?></span>
        <a href="logout.php" class="text-lg font-bold text-red-500">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_إمدادات.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم</th>
                    <th>وصف</th>
                    <th>حذف</th>
                    <th>تعديل</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <?php
                // Fetch records from backend
                $records = json_decode(file_get_contents('../backend/إمدادات.php'), true);
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?= $record['name'] ?></td>
                        <td><?= $record['description'] ?></td>
                        <td>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?= $record['id'] ?>)">حذف</button>
                        </td>
                        <td>
                            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_إمدادات.php?id=<?= $record['id'] ?>'">تعديل</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const searchInput = document.getElementById('search-input');
            const searchQuery = searchInput.value.trim();
            if (searchQuery === '') {
                fetchRecords();
            } else {
                fetchRecords(searchQuery);
            }
        }

        function fetchRecords(query = '') {
            const url = '../backend/إمدادات.php';
            const params = new URLSearchParams({
                query,
            });
            fetch(`${url}?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    const recordsTable = document.getElementById('records-table');
                    recordsTable.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.name}</td>
                            <td>${record.description}</td>
                            <td>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                            <td>
                                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_إمدادات.php?id=${record.id}'">تعديل</button>
                            </td>
                        `;
                        recordsTable.appendChild(row);
                    });
                })
                .catch(error => console.error(error));
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch(`../backend/إمدادات.php?id=${id}`, {
                    method: 'DELETE',
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            fetchRecords();
                        } else {
                            alert('حدث خطأ أثناء الحذف');
                        }
                    })
                    .catch(error => console.error(error));
            }
        }

        fetchRecords();
    </script>
</body>
</html>

Note: This code assumes that the backend API is already implemented and returns a JSON response with the list of records. The `fetchRecords` function is responsible for fetching the records from the backend API and updating the table with the received data. The `deleteRecord` function sends a DELETE request to the backend API to delete the specified record.