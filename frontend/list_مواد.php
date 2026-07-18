**list_مواد.php**

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
    <title>مواد</title>
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
        .search-bar input:focus {
            outline: none;
            box-shadow: 0 0 0 0.2rem #2d3748;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-indigo-500">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">مواد</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_مواد.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المادة</th>
                    <th>وصف المادة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $url = '../backend/مواد.php';
                $response = file_get_contents($url);
                $data = json_decode($response, true);
                foreach ($data as $record) {
                    ?>
                    <tr>
                        <td><?php echo $record['name']; ?></td>
                        <td><?php echo $record['description']; ?></td>
                        <td>
                            <a href="edit_مواد.php?id=<?php echo $record['id']; ?>" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(<?php echo $record['id']; ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Search bar filtering
        const searchInput = document.getElementById('search');
        const records = document.getElementById('records');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const recordsHtml = Array.from(records.children).map((record) => {
                const name = record.cells[0].textContent.toLowerCase();
                const description = record.cells[1].textContent.toLowerCase();
                if (name.includes(searchValue) || description.includes(searchValue)) {
                    return record.outerHTML;
                }
                return '';
            }).join('');
            records.innerHTML = recordsHtml;
        });

        // Delete record via AJAX
        function deleteRecord(id) {
            fetch('../backend/مواد.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error deleting record');
                }
            })
            .catch((error) => console.error(error));
        }
    </script>
</body>
</html>

**backend/مواد.php**

<?php
// Fetch records from database
// Replace with your actual database connection and query
$data = array(
    array('id' => 1, 'name' => 'مادة 1', 'description' => 'وصف مادة 1'),
    array('id' => 2, 'name' => 'مادة 2', 'description' => 'وصف مادة 2'),
    array('id' => 3, 'name' => 'مادة 3', 'description' => 'وصف مادة 3')
);
echo json_encode($data);
?>

Note: This code assumes you have a backend PHP script (`backend/مواد.php`) that fetches records from a database and returns them in JSON format. You'll need to replace this with your actual database connection and query.