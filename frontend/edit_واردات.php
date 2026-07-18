**edit_واردات.php**

<?php
// Include session validation
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get the ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/واردات.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit واردات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-lg font-bold text-slate-900 mb-4">Edit واردات</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-700">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-md" value="<?php echo $existingRecord['name']; ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-slate-700">Description:</label>
                <textarea id="description" name="description" class="block w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-md"><?php echo $existingRecord['description']; ?></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/واردات.php',
                    data: $(this).serialize() + '&id=' + <?php echo $id; ?>,
                    success: function(data) {
                        window.location.href = 'list_واردات.php';
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/واردات.php**

<?php
// Get the ID from URL
$id = $_GET['id'];

// Fetch existing record details from database
// Replace this with your actual database query
$existingRecord = array(
    'name' => 'Existing Record Name',
    'description' => 'Existing Record Description'
);

// Return the existing record details as JSON
echo json_encode($existingRecord);