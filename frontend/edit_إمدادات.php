**edit_إمدادات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/إمدادات.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if ($data) {
    // Populate form fields
    $name = $data['name'];
    $quantity = $data['quantity'];
    $unit_price = $data['unit_price'];
} else {
    // Handle error
    echo 'Error fetching data';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit إمدادات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-slate-900 mb-4">Edit إمدادات</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?php echo $name; ?>">
            </div>
            <div class="mb-4">
                <label for="quantity" class="block text-sm font-medium text-slate-900">Quantity:</label>
                <input type="number" id="quantity" name="quantity" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?php echo $quantity; ?>">
            </div>
            <div class="mb-4">
                <label for="unit_price" class="block text-sm font-medium text-slate-900">Unit Price:</label>
                <input type="number" id="unit_price" name="unit_price" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?php echo $unit_price; ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
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
                    url: '../backend/إمدادات.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_إمدادات.php';
                        } else {
                            alert('Error updating record');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/إمدادات.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

// Get id
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch existing record details
$query = "SELECT * FROM إمدادات WHERE id = '$id'";
$result = $conn->query($query);

// Check if data exists
if ($result->num_rows > 0) {
    // Get data
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    // Handle error
    echo 'Error fetching data';
}

// Close connection
$conn->close();
?>