**create_إنتاج.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
?>

<!-- Page content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-8">
    <div class="flex justify-between mb-4">
        <h1 class="text-2xl font-bold text-slate-900">إضافة إنتاج جديد</h1>
        <a href="list_إنتاج.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">رجوع</a>
    </div>

    <!-- Form -->
    <form id="create-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">اسم الإنتاج</label>
            <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">وصف الإنتاج</label>
            <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
        </div>

        <div class="mb-4">
            <label for="price" class="block text-gray-700 text-sm font-bold mb-2">سعر الإنتاج</label>
            <input type="number" id="price" name="price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">كمية الإنتاج</label>
            <input type="number" id="quantity" name="quantity" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة إنتاج</button>
    </form>
</div>

<!-- JavaScript -->
<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/إنتاج.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_إنتاج.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
require_once 'footer.php';
?>


**backend/إنتاج.php**

<?php
// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price']) && isset($_POST['quantity'])) {
    // Connect to database
    $conn = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute query
    $stmt = $conn->prepare('INSERT INTO إنتاج (name, description, price, quantity) VALUES (:name, :description, :price, :quantity)');
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':description', $_POST['description']);
    $stmt->bindParam(':price', $_POST['price']);
    $stmt->bindParam(':quantity', $_POST['quantity']);
    $stmt->execute();

    // Close connection
    $conn = null;

    // Output success message
    echo 'success';
} else {
    // Output error message
    echo 'Error: Invalid form data';
}
?>