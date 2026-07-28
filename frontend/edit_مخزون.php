**edit_مخزون.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/مخزون.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data is available
if ($data) {
    // Assign data to variables
    $name = $data['name'];
    $quantity = $data['quantity'];
    $price = $data['price'];
} else {
    // Redirect to list page if data is not available
    header('Location: list_مخزون.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit مخزون</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-slate-900 mb-4">Edit مخزون</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $name ?>">
            </div>
            <div class="mb-4">
                <label for="quantity" class="block text-sm font-medium text-slate-900">Quantity</label>
                <input type="number" id="quantity" name="quantity" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $quantity ?>">
            </div>
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-slate-900">Price</label>
                <input type="number" id="price" name="price" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $price ?>">
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 rounded-lg hover:bg-indigo-700 focus:ring-indigo-500 focus:border-indigo-500">Update</button>
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
                    url: '../backend/مخزون.php',
                    data: formData,
                    success: function(data) {
                        if (data.status === 'success') {
                            window.location.href = 'list_مخزون.php';
                        } else {
                            alert('Error updating record');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/مخزون.php**

<?php
// Check if id is provided
if (isset($_GET['id'])) {
    // Get id from URL
    $id = $_GET['id'];

    // Fetch existing record details from database
    $query = "SELECT * FROM مخزون WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    // Return data as JSON
    echo json_encode($data);
} else {
    // Return error message if id is not provided
    echo json_encode(array('error' => 'Invalid id'));
}
?>


**backend/update_mخزون.php**

<?php
// Check if id and data are provided
if (isset($_GET['id']) && isset($_POST['name']) && isset($_POST['quantity']) && isset($_POST['price'])) {
    // Get id, name, quantity, and price from URL and form data
    $id = $_GET['id'];
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    // Update record in database
    $query = "UPDATE مخزون SET name = '$name', quantity = '$quantity', price = '$price' WHERE id = '$id'";
    $result = mysqli_query($conn, $query);

    // Return success message as JSON
    echo json_encode(array('status' => 'success'));
} else {
    // Return error message if id or data is not provided
    echo json_encode(array('error' => 'Invalid request'));
}
?>