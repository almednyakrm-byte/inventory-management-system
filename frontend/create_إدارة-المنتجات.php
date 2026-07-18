**create_إدارة-المنتجات.php**

<?php
// Session validation
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
require_once 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة منتج جديد</h2>
        <form id="create-product-form" class="space-y-4">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label for="name" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">اسم المنتج</label>
                    <input type="text" id="name" name="name" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 xl:w-1/3 px-3 mb-6 md:mb-0">
                    <label for="category" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">فئة المنتج</label>
                    <select id="category" name="category" class="block appearance-none w-full bg-white text-gray-700 border border-gray-200 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                        <option value="">اختر فئة</option>
                        <option value="Electronics">الكهربائية</option>
                        <option value="Fashion">الملابس</option>
                        <option value="Home Goods">الأثاث</option>
                    </select>
                </div>
                <div class="w-full md:w-1/2 xl:w-1/3 px-3 mb-6 md:mb-0">
                    <label for="price" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">سعر المنتج</label>
                    <input type="number" id="price" name="price" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label for="description" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">وصف المنتج</label>
                    <textarea id="description" name="description" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required></textarea>
                </div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">إضافة منتج</button>
        </form>
    </div>
</div>

<?php
// Include footer
require_once 'footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-product-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/إدارة-المنتجات.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_إدارة-المنتجات.php';
                    } else {
                        alert('Error adding product');
                    }
                }
            });
        });
    });
</script>


**backend/إدارة-المنتجات.php**

<?php
// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract form data
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Insert data into database
    $query = "INSERT INTO products (name, category, price, description) VALUES ('$name', '$category', '$price', '$description')";
    $result = mysqli_query($conn, $query);

    // Check if data is inserted successfully
    if ($result) {
        echo 'success';
    } else {
        echo 'Error adding product';
    }
}
?>