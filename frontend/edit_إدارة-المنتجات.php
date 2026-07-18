**edit_إدارة-المنتجات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get product ID from URL
$id = $_GET['id'];

// Fetch product details via AJAX
$js = "
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: '../backend/إدارة-المنتجات.php?id=" . $id . "',
            dataType: 'json',
            success: function(data) {
                $('#name').val(data.name);
                $('#description').val(data.description);
                $('#price').val(data.price);
            }
        });
    });
";

// Include JavaScript code
echo "<script>$js</script>";

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المنتجات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .bg-slate-900 {
            background-color: #1a1a1a;
        }
        .text-indigo-500 {
            color: #6b6bcf;
        }
    </style>
</head>
<body class="bg-slate-900">
    <div class="max-w-md mx-auto p-4 mt-4 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl text-indigo-500 font-bold mb-4">تعديل المنتج</h2>
        <form id="product-form" method="POST" action="../backend/إدارة-المنتجات.php">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">اسم المنتج</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-700 bg-gray-100 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">وصف المنتج</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-700 bg-gray-100 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">سعر المنتج</label>
                <input type="number" id="price" name="price" class="block w-full p-2 mt-1 text-sm text-gray-700 bg-gray-100 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">حفظ التعديلات</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#product-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/إدارة-المنتجات.php',
                    data: $(this).serialize(),
                    success: function(data) {
                        if (data.success) {
                            window.location.href = 'list_إدارة-المنتجات.php';
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


**backend/إدارة-المنتجات.php**

<?php
// Check if product ID is set
if (!isset($_GET['id'])) {
    die('Product ID not set');
}

// Connect to database
$conn = new PDO('mysql:host=localhost;dbname=database', 'username', 'password');

// Fetch product details
$stmt = $conn->prepare('SELECT * FROM products WHERE id = :id');
$stmt->bindParam(':id', $_GET['id']);
$stmt->execute();
$product = $stmt->fetch();

// Return product details as JSON
header('Content-Type: application/json');
echo json_encode($product);

// Close database connection
$conn = null;


**backend/update_product.php**

<?php
// Check if product ID is set
if (!isset($_GET['id'])) {
    die('Product ID not set');
}

// Connect to database
$conn = new PDO('mysql:host=localhost;dbname=database', 'username', 'password');

// Update product details
$stmt = $conn->prepare('UPDATE products SET name = :name, description = :description, price = :price WHERE id = :id');
$stmt->bindParam(':id', $_GET['id']);
$stmt->bindParam(':name', $_POST['name']);
$stmt->bindParam(':description', $_POST['description']);
$stmt->bindParam(':price', $_POST['price']);
$stmt->execute();

// Return success message
header('Content-Type: application/json');
echo json_encode(array('success' => true));

// Close database connection
$conn = null;