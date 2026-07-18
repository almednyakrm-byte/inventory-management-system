**edit_إدارة-المخزون.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Validate id
if (empty($id)) {
    header('Location: list_إدارة-المخزون.php');
    exit;
}

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/إدارة-المخزون.php?id=' . $id), true);

// Validate existing record
if (empty($existingRecord)) {
    header('Location: list_إدارة-المخزون.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل إدارة المخزون</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .bg-slate-900 {
            background-color: #1a1a1a;
        }
        .text-indigo-500 {
            color: #6b46c1;
        }
    </style>
</head>
<body class="bg-slate-900 text-white">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-2xl font-bold mb-4">تعديل إدارة المخزون</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium mb-2">اسم المخزون:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium mb-2">وصف المخزون:</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" rows="4"><?= $existingRecord['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">حفظ التعديلات</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/إدارة-المخزون.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_إدارة-المخزون.php';
                        } else {
                            alert('Error: ' + response.error);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/إدارة-المخزون.php**

<?php
// Validate id
$id = $_GET['id'];

// Fetch existing record details from database
$existingRecord = // fetch record from database using $id

// Return existing record details as JSON
echo json_encode($existingRecord);


**backend/update_إدارة-المخزون.php**

<?php
// Validate id
$id = $_GET['id'];

// Validate form data
$name = $_POST['name'];
$description = $_POST['description'];

// Update existing record in database
// ...

// Return success message as JSON
echo json_encode(['success' => true]);