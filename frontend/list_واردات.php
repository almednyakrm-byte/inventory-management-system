**list_واردات.php**

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
    <title>واردات</title>
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
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الصفحة الرئيسية</a>
        <span class="text-indigo-500 font-bold">مرحباً <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-indigo-500 font-bold">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-slate-900">واردات</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_واردات.php'">إضافة عنصر جديد</button>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>رقم الوارد</th>
                    <th>تاريخ الوارد</th>
                    <th>المبلغ</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?php echo $record['id']; ?></td>
                        <td><?php echo $record['date']; ?></td>
                        <td><?php echo $record['amount']; ?></td>
                        <td>
                            <a href="edit_واردات.php?id=<?php echo $record['id']; ?>" class="text-indigo-500 font-bold">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?php echo $record['id']; ?>)">حذف</button>
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
            if (searchQuery) {
                fetchRecords(searchQuery);
            } else {
                fetchRecords();
            }
        }

        function fetchRecords(searchQuery = '') {
            const url = '../backend/واردات.php';
            const params = new URLSearchParams({
                search: searchQuery
            });
            const fetchUrl = `${url}?${params.toString()}`;
            fetch(fetchUrl)
                .then(response => response.json())
                .then(data => {
                    const recordsTable = document.getElementById('records-table');
                    recordsTable.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.id}</td>
                            <td>${record.date}</td>
                            <td>${record.amount}</td>
                            <td>
                                <a href="edit_واردات.php?id=${record.id}" class="text-indigo-500 font-bold">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        recordsTable.appendChild(row);
                    });
                })
                .catch(error => console.error(error));
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch(`../backend/واردات.php?delete=${id}`, {
                    method: 'DELETE'
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
    </script>
</body>
</html>

<?php
function fetchRecords() {
    $url = '../backend/واردات.php';
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    return $data;
}
?>


**backend/واردات.php**

<?php
// Fetch records from database
$records = array();
$records[] = array('id' => 1, 'date' => '2022-01-01', 'amount' => 1000);
$records[] = array('id' => 2, 'date' => '2022-01-15', 'amount' => 2000);
$records[] = array('id' => 3, 'date' => '2022-02-01', 'amount' => 3000);

// Search query
$searchQuery = $_GET['search'] ?? '';

// Filter records based on search query
if ($searchQuery) {
    $filteredRecords = array_filter($records, function($record) use ($searchQuery) {
        return strpos($record['date'], $searchQuery) !== false || strpos($record['amount'], $searchQuery) !== false;
    });
    $records = $filteredRecords;
}

// Delete record
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $records = array_filter($records, function($record) use ($id) {
        return $record['id'] !== $id;
    });
}

// Output records as JSON
header('Content-Type: application/json');
echo json_encode($records);
?>