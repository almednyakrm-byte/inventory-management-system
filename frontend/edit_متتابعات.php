**edit_متتابعات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/متتابعات.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if ($data) {
    $name = $data['name'];
    $description = $data['description'];
} else {
    header('Location: list_متتابعات.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit متتابعات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .slate-900 {
            color: #1a1d23;
        }
        .indigo-500 {
            color: #6b6bcf;
        }
    </style>
</head>
<body>
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold slate-900 mb-4">Edit متتابعات</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium slate-900">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $name ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium slate-900">Description:</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" rows="4"><?= $description ?></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'PUT',
                    url: '../backend/متتابعات.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.success) {
                            window.location.href = 'list_متتابعات.php';
                        } else {
                            alert(data.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/متتابعات.php**

<?php
// Check if ID exists
if (!isset($_GET['id'])) {
    header('Location: list_متتابعات.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$query = "SELECT * FROM متتابعات WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Check if data exists
if ($data) {
    echo json_encode($data);
} else {
    header('Location: list_متتابعات.php');
    exit;
}
?>


**Note:** Replace `../backend/متتابعات.php` with your actual backend script URL. Also, make sure to replace `list_متتابعات.php` with your actual list page URL.