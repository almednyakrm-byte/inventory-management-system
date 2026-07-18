**list_فواتير.php**

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
    <title>فواتير</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
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
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: center;
        }
        .table th {
            background-color: #1a1d23;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
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
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-indigo-500">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">فواتير</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_فواتير.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>رقم الفاتورة</th>
                    <th>تاريخ الفاتورة</th>
                    <th>مبلغ الفاتورة</th>
                    <th>حالة الفاتورة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?php echo $record['invoice_number']; ?></td>
                        <td><?php echo $record['invoice_date']; ?></td>
                        <td><?php echo $record['invoice_amount']; ?></td>
                        <td><?php echo $record['invoice_status']; ?></td>
                        <td>
                            <a href="edit_فواتير.php?id=<?php echo $record['id']; ?>" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
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
                fetch('../backend/فواتير.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        const recordsTable = document.getElementById('records-table');
                        recordsTable.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.invoice_number}</td>
                                <td>${record.invoice_date}</td>
                                <td>${record.invoice_amount}</td>
                                <td>${record.invoice_status}</td>
                                <td>
                                    <a href="edit_فواتير.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/فواتير.php')
                    .then(response => response.json())
                    .then(data => {
                        const recordsTable = document.getElementById('records-table');
                        recordsTable.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.invoice_number}</td>
                                <td>${record.invoice_date}</td>
                                <td>${record.invoice_amount}</td>
                                <td>${record.invoice_status}</td>
                                <td>
                                    <a href="edit_فواتير.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    });
            }
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف الفاتورة؟')) {
                fetch('../backend/فواتير.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف الفاتورة بنجاح');
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف الفاتورة');
                    }
                })
                .catch(error => console.error(error));
            }
        }

        function fetchRecords() {
            return fetch('../backend/فواتير.php')
                .then(response => response.json())
                .then(data => data);
        }
    </script>
</body>
</html>


**backend/فواتير.php**

<?php
// Fetch records from database
$records = array();
$records[] = array(
    'id' => 1,
    'invoice_number' => 'INV001',
    'invoice_date' => '2022-01-01',
    'invoice_amount' => 100.00,
    'invoice_status' => 'مفعلة'
);
$records[] = array(
    'id' => 2,
    'invoice_number' => 'INV002',
    'invoice_date' => '2022-01-15',
    'invoice_amount' => 200.00,
    'invoice_status' => 'مفعلة'
);
$records[] = array(
    'id' => 3,
    'invoice_number' => 'INV003',
    'invoice_date' => '2022-02-01',
    'invoice_amount' => 300.00,
    'invoice_status' => 'مفعلة'
);

// Search functionality
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $records = array_filter($records, function($record) use ($searchQuery) {
        return strpos($record['invoice_number'], $searchQuery) !== false ||
               strpos($record['invoice_date'], $searchQuery) !== false ||
               strpos($record['invoice_amount'], $searchQuery) !== false ||
               strpos($record['invoice_status'], $searchQuery) !== false;
    });
}

// Delete record
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_POST['id'];
    // Delete record from database
    // ...
    echo json_encode(array('success' => true));
    exit;
}

// Output records
echo json_encode($records);
exit;
?>

Note: This is a basic example and you should replace the backend code with your actual database connection and query logic.