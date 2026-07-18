**edit_reports.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get report ID from URL
$id = $_GET['id'];

// Fetch report details via AJAX
$report = json_decode(file_get_contents('../backend/reports.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Report</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-slate-900 text-lg font-bold mb-4">Edit Report</h2>
        <form id="edit-report-form" class="space-y-4">
            <div>
                <label for="title" class="text-slate-900 text-sm font-bold">Title:</label>
                <input type="text" id="title" name="title" class="w-full p-2 text-slate-900 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500" value="<?= $report['title'] ?>">
            </div>
            <div>
                <label for="description" class="text-slate-900 text-sm font-bold">Description:</label>
                <textarea id="description" name="description" class="w-full p-2 text-slate-900 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500"><?= $report['description'] ?></textarea>
            </div>
            <div>
                <label for="status" class="text-slate-900 text-sm font-bold">Status:</label>
                <select id="status" name="status" class="w-full p-2 text-slate-900 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500">
                    <option value="pending" <?= $report['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="in_progress" <?= $report['status'] == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="completed" <?= $report['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                </select>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">Update Report</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-report-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/reports.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_reports.php';
                        } else {
                            alert('Error updating report');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/reports.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get report ID from URL
$id = $_GET['id'];

// Fetch report details from database
$report = get_report($id);

// Return report details as JSON
echo json_encode($report);

function get_report($id) {
    // Connect to database
    $conn = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute query
    $stmt = $conn->prepare('SELECT * FROM reports WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Fetch report details
    $report = $stmt->fetch(PDO::FETCH_ASSOC);

    // Close database connection
    $conn = null;

    return $report;
}