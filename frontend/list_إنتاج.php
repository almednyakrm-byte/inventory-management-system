**list_إنتاج.php**

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
    <title>إنتاج</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
        }
        .header .nav-link {
            color: #fff;
        }
        .header .nav-link:hover {
            color: #ccc;
        }
        .table {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .search-bar {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 50%;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
        }
        .search-bar button[type="submit"] {
            background-color: #1a1d23;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-bar button[type="submit"]:hover {
            background-color: #1a1d23;
            color: #ccc;
        }
    </style>
</head>
<body>
    <div class="header py-4">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center">
                <a href="index.php" class="nav-link">الرئيسية</a>
                <div class="flex items-center">
                    <span class="text-lg font-bold text-slate-900 mr-2">مرحباً</span>
                    <span class="text-lg font-bold text-indigo-500"><?= $_SESSION['username'] ?></span>
                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2" onclick="location.href='logout.php'">تسجيل الخروج</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container mx-auto px-4 pt-4">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-slate-900">إنتاج</h2>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_إنتاج.php'">إضافة جديد</button>
        </div>
        <div class="flex justify-between items-center mt-4">
            <input type="search" class="search-bar" placeholder="بحث...">
            <button type="submit" class="search-bar">بحث</button>
        </div>
        <table class="table w-full mt-4">
            <thead>
                <tr>
                    <th>رقم السجل</th>
                    <th>اسم المنتج</th>
                    <th>حالة المنتج</th>
                    <th>تاريخ الإنتاج</th>
                    <th>تاريخ النهاية</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $response = file_get_contents('../backend/إنتاج.php');
                $records = json_decode($response, true);
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?= $record['id'] ?></td>
                        <td><?= $record['name'] ?></td>
                        <td><?= $record['status'] ?></td>
                        <td><?= $record['production_date'] ?></td>
                        <td><?= $record['end_date'] ?></td>
                        <td>
                            <a href="edit_إنتاج.php?id=<?= $record['id'] ?>" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?= $record['id'] ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function deleteRecord(id) {
            fetch('../backend/إنتاج.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('تم حذف السجل بنجاح');
                    location.reload();
                } else {
                    alert('حدث خطأ أثناء حذف السجل');
                }
            })
            .catch(error => console.error(error));
        }
    </script>
</body>
</html>

This code includes a premium Tailwind UI layout with a header navigation, table showing list of records, and a search bar. It also includes AJAX JavaScript code to fetch records from the backend and delete records using a DELETE request.