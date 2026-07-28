<?php
// edit_صفقات.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_صفقات.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل صفقة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-5xl mx-auto p-4 pt-6 md:p-6 lg:p-8 bg-white rounded shadow-md">
        <h2 class="text-3xl text-indigo-500 font-bold mb-4">تعديل صفقة</h2>
        <form id="edit-صفقات-form">
            <div class="mb-4">
                <label for="name" class="block text-slate-900 text-sm font-bold mb-2">اسم الصفقة</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-slate-900 bg-gray-100 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-slate-900 text-sm font-bold mb-2">وصف الصفقة</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-slate-900 bg-gray-100 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">حفظ التغييرات</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            var id = '<?php echo $id; ?>';
            $.ajax({
                type: 'GET',
                url: '../backend/صفقات.php?id=' + id,
                dataType: 'json',
                success: function(data) {
                    $('#name').val(data.name);
                    $('#description').val(data.description);
                }
            });

            $('#edit-صفقات-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/صفقات.php',
                    data: formData,
                    success: function(data) {
                        window.location.href = 'list_صفقات.php';
                    }
                });
            });
        });
    </script>
</body>
</html>