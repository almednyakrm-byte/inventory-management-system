**create_reports.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
require_once 'nav.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-slate-900 font-bold text-lg mb-4">Create Report</h2>
        <form id="create-report-form">
            <div class="mb-4">
                <label for="title" class="block text-slate-900 text-sm font-bold mb-2">Title</label>
                <input type="text" id="title" name="title" class="block w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-slate-900 text-sm font-bold mb-2">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
            </div>
            <div class="mb-4">
                <label for="status" class="block text-slate-900 text-sm font-bold mb-2">Status</label>
                <select id="status" name="status" class="block w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Select Status</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Create Report</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#create-report-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/reports.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_reports.php';
                    } else {
                        alert('Error creating report');
                    }
                }
            });
        });
    });
</script>

<?php
require_once 'footer.php';
?>


**backend/reports.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Connect to database
require_once 'db.php';

// Get form data
$title = $_POST['title'];
$description = $_POST['description'];
$status = $_POST['status'];

// Insert report into database
$query = "INSERT INTO reports (title, description, status, created_by) VALUES ('$title', '$description', '$status', '".$_SESSION['user_id']."')";
mysqli_query($conn, $query);

// Redirect to list reports page
header('Location: list_reports.php');
exit;
?>


**Note:** This code assumes you have a `db.php` file that connects to your database and a `header.php` and `footer.php` file that includes the HTML header and footer respectively. You will need to modify the code to fit your specific database schema and requirements. Additionally, this code does not include any validation or error handling, you should add this to make the code more robust.