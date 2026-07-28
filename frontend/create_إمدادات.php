**create_إمدادات.php**

<?php
// Session validation
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
?>

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold text-slate-900 mb-4">إضافة إمدادات جديدة</h1>
    <form id="create-supplies-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label for="name" class="block text-slate-900 text-sm font-bold mb-2">اسم الإمداد</label>
            <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-900 leading-tight focus:outline-none focus:shadow-outline" placeholder="اسم الإمداد">
        </div>
        <div class="mb-4">
            <label for="quantity" class="block text-slate-900 text-sm font-bold mb-2">الكمية</label>
            <input type="number" id="quantity" name="quantity" class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-900 leading-tight focus:outline-none focus:shadow-outline" placeholder="الكمية">
        </div>
        <div class="mb-4">
            <label for="unit_price" class="block text-slate-900 text-sm font-bold mb-2">سعر الوحدة</label>
            <input type="number" id="unit_price" name="unit_price" class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-900 leading-tight focus:outline-none focus:shadow-outline" placeholder="سعر الوحدة">
        </div>
        <div class="mb-4">
            <label for="supplier" class="block text-slate-900 text-sm font-bold mb-2">المورد</label>
            <select id="supplier" name="supplier" class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-900 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">اختر موردًا</option>
                <!-- Suppliers list will be populated here -->
            </select>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">حفظ</button>
    </form>
</div>

<script>
    // Get suppliers list
    $.ajax({
        type: 'GET',
        url: '../backend/suppliers.php',
        success: function(data) {
            var suppliers = JSON.parse(data);
            $.each(suppliers, function(index, supplier) {
                $('#supplier').append('<option value="' + supplier.id + '">' + supplier.name + '</option>');
            });
        }
    });

    // Submit form via AJAX
    $('#create-supplies-form').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: '../backend/إمدادات.php',
            data: formData,
            success: function(data) {
                if (data === 'success') {
                    window.location.href = 'list_إمدادات.php';
                } else {
                    alert('Error: ' + data);
                }
            }
        });
    });
</script>

<?php
// Include footer
require_once 'footer.php';
?>


**إمدادات.php (backend)**

<?php
// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['quantity']) && isset($_POST['unit_price']) && isset($_POST['supplier'])) {
    // Insert data into database
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $unit_price = $_POST['unit_price'];
    $supplier_id = $_POST['supplier'];

    // Database connection
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert query
    $sql = "INSERT INTO إمدادات (name, quantity, unit_price, supplier_id) VALUES ('$name', '$quantity', '$unit_price', '$supplier_id')";

    // Execute query
    if ($conn->query($sql) === TRUE) {
        echo 'success';
    } else {
        echo 'Error: ' . $sql . '<br>' . $conn->error;
    }

    // Close connection
    $conn->close();
}
?>