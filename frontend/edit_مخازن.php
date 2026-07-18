<?php
// Session validation
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Include database connection
include '../backend/db.php';

// Check if id is valid
if (!is_numeric($id)) {
    header('Location: list_مخازن.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مخزن</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 bg-slate-900 p-8 rounded-xl shadow-md">
        <h2 class="text-3xl text-indigo-500 font-bold mb-4">تعديل مخزن</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-indigo-500 text-sm font-bold mb-2">اسم المخزن</label>
                <input type="text" id="name" name="name" class="bg-slate-900 border-indigo-500 text-indigo-500 rounded-lg py-2 px-4 w-full">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-indigo-500 text-sm font-bold mb-2">وصف المخزن</label>
                <textarea id="description" name="description" class="bg-slate-900 border-indigo-500 text-indigo-500 rounded-lg py-2 px-4 w-full h-32"></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-slate-900 font-bold py-2 px-4 rounded-lg w-full">حفظ التعديلات</button>
        </form>
    </div>

    <script>
        // Fetch existing record details
        fetch('../backend/مخازن.php?id=<?= $id ?>')
            .then(response => response.json())
            .then(data => {
                document.getElementById('name').value = data.name;
                document.getElementById('description').value = data.description;
            });

        // Submit form using AJAX
        document.getElementById('edit-form').addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            fetch('../backend/مخازن.php', {
                method: 'PUT',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_مخازن.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>