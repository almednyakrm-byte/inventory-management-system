**edit_مواد.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get the ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$data = json_decode(file_get_contents('../backend/مواد.php?id=' . $id), true);

// Check if data is available
if ($data) {
    // Set form fields
    $name = $data['name'];
    $description = $data['description'];
    $price = $data['price'];
} else {
    // Redirect to error page if data is not available
    header('Location: error.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Material</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-slate-900 mb-4">Edit Material</h2>
        <form id="edit-material-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500" value="<?= $name ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-slate-900">Description:</label>
                <textarea id="description" name="description" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500" rows="4"><?= $description ?></textarea>
            </div>
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-slate-900">Price:</label>
                <input type="number" id="price" name="price" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500" value="<?= $price ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">Update Material</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-material-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مواد.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error updating material');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/مواد.php**

<?php
// Check if ID is provided
if (isset($_GET['id'])) {
    // Connect to database
    $conn = mysqli_connect('localhost', 'username', 'password', 'database');
    
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    // Get the ID from URL
    $id = $_GET['id'];
    
    // SQL query to fetch existing record details
    $sql = "SELECT * FROM materials WHERE id = '$id'";
    
    // Execute query
    $result = mysqli_query($conn, $sql);
    
    // Check if data is available
    if (mysqli_num_rows($result) > 0) {
        // Fetch data
        $data = mysqli_fetch_assoc($result);
        
        // Close connection
        mysqli_close($conn);
        
        // Output data
        echo json_encode($data);
    } else {
        // Close connection
        mysqli_close($conn);
        
        // Output error
        echo json_encode(array('error' => 'No data found'));
    }
} else {
    // Output error
    echo json_encode(array('error' => 'ID not provided'));
}
?>