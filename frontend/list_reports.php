**list_reports.php**

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
    <title>Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2d3748;
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
            background-color: #2d3748;
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
            box-shadow: 0 0 0 0.25rem rgba(13, 130, 184, 0.5);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">Back to Index</a>
        <span class="text-indigo-500">Welcome, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-indigo-500">Logout</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">Reports</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_reports.php'">Add New Item</button>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="Search...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchReports()">Search</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Report Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="report-list">
                <?php
                // Fetch reports from backend
                $reports = json_decode(file_get_contents('../backend/reports.php'), true);
                foreach ($reports as $report) {
                    echo '<tr>';
                    echo '<td>' . $report['id'] . '</td>';
                    echo '<td>' . $report['name'] . '</td>';
                    echo '<td>';
                    echo '<a href="edit_reports.php?id=' . $report['id'] . '">Edit</a> | ';
                    echo '<button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteReport(' . $report['id'] . ')">Delete</button>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchReports() {
            const searchInput = document.getElementById('search-input').value;
            fetch('../backend/reports.php?search=' + searchInput)
                .then(response => response.json())
                .then(data => {
                    const reportList = document.getElementById('report-list');
                    reportList.innerHTML = '';
                    data.forEach(report => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${report.id}</td>
                            <td>${report.name}</td>
                            <td>
                                <a href="edit_reports.php?id=${report.id}">Edit</a> | 
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteReport(${report.id})">Delete</button>
                            </td>
                        `;
                        reportList.appendChild(row);
                    });
                });
        }

        function deleteReport(id) {
            if (confirm('Are you sure you want to delete this report?')) {
                fetch('../backend/reports.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Report deleted successfully!');
                        window.location.reload();
                    } else {
                        alert('Error deleting report!');
                    }
                });
            }
        }
    </script>
</body>
</html>

**backend/reports.php**

<?php
// Fetch reports from database
$reports = array();
$reports[] = array('id' => 1, 'name' => 'Report 1');
$reports[] = array('id' => 2, 'name' => 'Report 2');
$reports[] = array('id' => 3, 'name' => 'Report 3');

// Search functionality
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $reports = array_filter($reports, function($report) use ($searchTerm) {
        return strpos($report['name'], $searchTerm) !== false;
    });
}

// Output reports in JSON format
header('Content-Type: application/json');
echo json_encode($reports);