<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Set module slug
$mod_slug = 'products';

// Set page title
$page_title = 'Create Product';

// Include header
include 'header.php';
?>

<main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-slate-900">Create Product</h3>
                <p class="mt-1 text-sm text-slate-600">Please fill in the form to create a new product.</p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form id="create-product-form">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="product_name" class="block text-sm font-medium text-slate-700">Product Name</label>
                                <div class="mt-1">
                                    <input type="text" id="product_name" name="product_name" class="block w-full shadow-sm sm:text-sm border border-slate-300 rounded-md">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="product_description" class="block text-sm font-medium text-slate-700">Product Description</label>
                                <div class="mt-1">
                                    <textarea id="product_description" name="product_description" class="block w-full shadow-sm sm:text-sm border border-slate-300 rounded-md"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="product_price" class="block text-sm font-medium text-slate-700">Product Price</label>
                                <div class="mt-1">
                                    <input type="number" id="product_price" name="product_price" class="block w-full shadow-sm sm:text-sm border border-slate-300 rounded-md">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="product_category" class="block text-sm font-medium text-slate-700">Product Category</label>
                                <div class="mt-1">
                                    <select id="product_category" name="product_category" class="block w-full shadow-sm sm:text-sm border border-slate-300 rounded-md">
                                        <option value="">Select Category</option>
                                        <option value="Electronics">Electronics</option>
                                        <option value="Fashion">Fashion</option>
                                        <option value="Home Goods">Home Goods</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-3 sm:col-span-2">
                                <label for="product_image" class="block text-sm font-medium text-slate-700">Product Image</label>
                                <div class="mt-1">
                                    <input type="file" id="product_image" name="product_image" class="block w-full shadow-sm sm:text-sm border border-slate-300 rounded-md">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-slate-900 text-right sm:px-6">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create Product</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#create-product-form').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: '../backend/products.php',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                }
            });
        });
    });
</script>