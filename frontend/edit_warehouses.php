<?php
// edit_warehouses.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_warehouses.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Warehouse</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-6 bg-slate-900 rounded-lg shadow-lg">
        <h2 class="text-2xl text-indigo-500 font-bold mb-4">Edit Warehouse</h2>
        <form id="edit-warehouse-form">
            <div class="mb-4">
                <label for="name" class="block text-sm text-indigo-500 font-bold mb-2">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 bg-slate-800 text-indigo-500 border border-indigo-500 rounded">
            </div>
            <div class="mb-4">
                <label for="address" class="block text-sm text-indigo-500 font-bold mb-2">Address</label>
                <input type="text" id="address" name="address" class="block w-full p-2 bg-slate-800 text-indigo-500 border border-indigo-500 rounded">
            </div>
            <div class="mb-4">
                <label for="city" class="block text-sm text-indigo-500 font-bold mb-2">City</label>
                <input type="text" id="city" name="city" class="block w-full p-2 bg-slate-800 text-indigo-500 border border-indigo-500 rounded">
            </div>
            <div class="mb-4">
                <label for="state" class="block text-sm text-indigo-500 font-bold mb-2">State</label>
                <input type="text" id="state" name="state" class="block w-full p-2 bg-slate-800 text-indigo-500 border border-indigo-500 rounded">
            </div>
            <div class="mb-4">
                <label for="zip" class="block text-sm text-indigo-500 font-bold mb-2">Zip</label>
                <input type="text" id="zip" name="zip" class="block w-full p-2 bg-slate-800 text-indigo-500 border border-indigo-500 rounded">
            </div>
            <button type="submit" class="w-full p-2 bg-indigo-500 text-slate-900 font-bold rounded">Update Warehouse</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            var id = '<?php echo $id; ?>';
            $.ajax({
                type: 'GET',
                url: '../backend/warehouses.php?id=' + id,
                dataType: 'json',
                success: function(data) {
                    $('#name').val(data.name);
                    $('#address').val(data.address);
                    $('#city').val(data.city);
                    $('#state').val(data.state);
                    $('#zip').val(data.zip);
                }
            });

            $('#edit-warehouse-form').submit(function(e) {
                e.preventDefault();
                var formData = {
                    'id': id,
                    'name': $('#name').val(),
                    'address': $('#address').val(),
                    'city': $('#city').val(),
                    'state': $('#state').val(),
                    'zip': $('#zip').val()
                };
                $.ajax({
                    type: 'PUT',
                    url: '../backend/warehouses.php',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    success: function(data) {
                        window.location.href = 'list_warehouses.php';
                    }
                });
            });
        });
    </script>
</body>
</html>