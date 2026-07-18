**edit_الطلبات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$record = json_decode(file_get_contents('../backend/الطلبات.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل الطلبات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-slate-900 font-bold text-lg mb-4">تعديل الطلبات</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="text-slate-900 font-bold">الاسم:</label>
                <input type="text" id="name" name="name" class="w-full p-2 rounded-md border border-slate-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $record['name'] ?>">
            </div>
            <div>
                <label for="description" class="text-slate-900 font-bold">الوصف:</label>
                <textarea id="description" name="description" class="w-full p-2 rounded-md border border-slate-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"><?= $record['description'] ?></textarea>
            </div>
            <div>
                <label for="status" class="text-slate-900 font-bold">الحالة:</label>
                <select id="status" name="status" class="w-full p-2 rounded-md border border-slate-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="pending" <?= $record['status'] == 'pending' ? 'selected' : '' ?>>pending</option>
                    <option value="in_progress" <?= $record['status'] == 'in_progress' ? 'selected' : '' ?>>in_progress</option>
                    <option value="completed" <?= $record['status'] == 'completed' ? 'selected' : '' ?>>completed</option>
                </select>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">تعديل</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/الطلبات.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_الطلبات.php';
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/الطلبات.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(array('success' => false, 'message' => 'ID not set'));
    exit;
}

// Get ID
$id = $_GET['id'];

// Fetch existing record details
$record = array();
// Your database query to fetch the record
// For example:
// $record = $db->query("SELECT * FROM orders WHERE id = '$id'")->fetch_assoc();

echo json_encode($record);