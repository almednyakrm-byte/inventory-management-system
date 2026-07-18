**create_المنتجات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include Tailwind CSS
?>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<?php
// Include navigation
include 'navigation.php';
?>

<div class="container mx-auto p-4 mt-12">
    <h1 class="text-3xl font-bold text-slate-900">إضافة منتج جديد</h1>
    <form id="create-product-form" class="bg-white p-4 mt-4 shadow-md rounded-md">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">اسم المنتج</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-slate-900 placeholder:text-slate-400 border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="اسم المنتج">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-900">وصف المنتج</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-slate-900 placeholder:text-slate-400 border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="وصف المنتج"></textarea>
            </div>
            <div>
                <label for="price" class="block text-sm font-medium text-slate-900">سعر المنتج</label>
                <input type="number" id="price" name="price" class="block w-full p-2 mt-1 text-sm text-slate-900 placeholder:text-slate-400 border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="سعر المنتج">
            </div>
            <div>
                <label for="stock" class="block text-sm font-medium text-slate-900">مخزون المنتج</label>
                <input type="number" id="stock" name="stock" class="block w-full p-2 mt-1 text-sm text-slate-900 placeholder:text-slate-400 border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="مخزون المنتج">
            </div>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">إضافة منتج</button>
    </form>
</div>

<?php
// Include footer
include 'footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-product-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/المنتجات.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_المنتجات.php';
                    } else {
                        alert('Error adding product');
                    }
                }
            });
        });
    });
</script>


**Note:** This code assumes you have jQuery and Tailwind CSS installed. You may need to adjust the CSS classes and JavaScript code to match your specific requirements. Additionally, you will need to create the `header.php`, `navigation.php`, and `footer.php` files to include the necessary HTML structure.