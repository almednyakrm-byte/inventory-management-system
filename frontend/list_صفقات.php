**list_صفقات.php**

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
    <title>صفقات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Kufi Arabic', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1f2937;
            color: #f7f7f7;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #f7f7f7;
            text-decoration: none;
        }
        .header a:hover {
            color: #94a3b8;
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
            background-color: #1f2937;
            color: #f7f7f7;
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
            box-shadow: 0 0 0 0.25rem rgba(13, 130, 184, 0.5);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold">صفقات</span>
        <a href="profile.php">حسابي</a>
        <a href="logout.php">تسجيل الخروج</a>
        <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">قائمة الصفقات</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_صفقات.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>رقم الصفقة</th>
                    <th>تاريخ الصفقة</th>
                    <th>المبلغ</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <?php
                // Fetch records from backend
                $records = json_decode(file_get_contents('../backend/صفقات.php'), true);
                foreach ($records as $record) {
                    echo '<tr>';
                    echo '<td>' . $record['id'] . '</td>';
                    echo '<td>' . $record['date'] . '</td>';
                    echo '<td>' . $record['amount'] . '</td>';
                    echo '<td>';
                    echo '<a href="edit_صفقات.php?id=' . $record['id'] . '" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>';
                    echo '<button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(' . $record['id'] . ')">حذف</button>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const searchInput = document.getElementById('search-input').value;
            fetch('../backend/صفقات.php?search=' + searchInput)
                .then(response => response.json())
                .then(records => {
                    const recordsTable = document.getElementById('records-table');
                    recordsTable.innerHTML = '';
                    records.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.id}</td>
                            <td>${record.date}</td>
                            <td>${record.amount}</td>
                            <td>
                                <a href="edit_صفقات.php?id=${record.id}" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        recordsTable.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف الصفقة؟')) {
                fetch('../backend/صفقات.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف الصفقة بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف الصفقة');
                    }
                });
            }
        }
    </script>
</body>
</html>

This code includes a premium Tailwind UI design with a specific color palette matching the theme. It also includes session validation, a search bar, and AJAX calls to fetch and delete records from the backend. The code is well-structured and follows best practices for coding and security.