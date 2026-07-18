**edit_المنتجات.php**

<?php
// Include session validation
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get product ID from URL
$id = $_GET['id'];

// Fetch existing product details via GET
$product = json_decode(file_get_contents('../backend/المنتجات.php?id=' . $id), true);

// Check if product exists
if (empty($product)) {
    echo 'Product not found';
    exit;
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل المنتج</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h1 class="text-2xl font-bold text-slate-900 mb-4">تعديل المنتج</h1>
        <form id="edit-product-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-700">اسم المنتج:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $product['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-slate-700">وصف المنتج:</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" rows="4"><?= $product['description'] ?></textarea>
            </div>
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-slate-700">سعر المنتج:</label>
                <input type="number" id="price" name="price" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $product['price'] ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل المنتج</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-product-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/المنتجات.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error updating product');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/المنتجات.php**

<?php
// Check if product ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'Product ID not set'));
    exit;
}

// Get product ID
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get product details
$query = "SELECT * FROM products WHERE id = '$id'";
$result = $conn->query($query);

// Check if product exists
if ($result->num_rows > 0) {
    // Fetch product details
    $product = $result->fetch_assoc();
    echo json_encode($product);
} else {
    echo json_encode(array('error' => 'Product not found'));
}

// Close database connection
$conn->close();
?>


**backend/edit_product.php**

<?php
// Check if product ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'Product ID not set'));
    exit;
}

// Get product ID
$id = $_GET['id'];

// Get product data
$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to update product
$query = "UPDATE products SET name = '$name', description = '$description', price = '$price' WHERE id = '$id'";
$result = $conn->query($query);

// Check if update was successful
if ($result) {
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('error' => 'Error updating product'));
}

// Close database connection
$conn->close();
?>