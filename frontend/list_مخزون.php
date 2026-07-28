**list_مخزون.php**

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
    <title>مخزون</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
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
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
        }
        .table th {
            background-color: #2d3748;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar button {
            background-color: #2d3748;
            color: #fff;
            border: none;
            padding: 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .search-bar button:hover {
            background-color: #3b4453;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-white">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">مخزون</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_مخزون.php'">إضافة عنصر جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>الرقم</th>
                    <th>الاسم</th>
                    <th>الوصف</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be displayed here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch API to get list records
        async function getRecords() {
            try {
                const response = await fetch('../backend/مخزون.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                displayRecords(data);
            } catch (error) {
                console.error(error);
            }
        }

        // Display records in table
        function displayRecords(records) {
            const tbody = document.getElementById('records');
            tbody.innerHTML = '';
            records.forEach((record, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${record.اسم}</td>
                    <td>${record.وصف}</td>
                    <td>
                        <a href="edit_مخزون.php?id=${record.id}" class="text-blue-500 hover:text-blue-700">تعديل</a>
                        <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        // Search records
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchValue = searchInput.value.trim();
            if (searchValue !== '') {
                fetch('../backend/مخزون.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    params: {
                        search: searchValue
                    }
                })
                .then(response => response.json())
                .then(data => displayRecords(data))
                .catch(error => console.error(error));
            } else {
                getRecords();
            }
        }

        // Delete record
        async function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا العنصر؟')) {
                try {
                    const response = await fetch('../backend/مخزون.php', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id })
                    });
                    if (response.ok) {
                        getRecords();
                    } else {
                        alert('حدث خطأ أثناء الحذف');
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        }

        // Initialize records
        getRecords();
    </script>
</body>
</html>

Note: This code assumes that you have a backend script (`../backend/مخزون.php`) that handles GET and DELETE requests to retrieve and delete records, respectively. You will need to implement this script separately.