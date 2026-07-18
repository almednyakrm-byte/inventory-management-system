**create_الأصناف.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة صنف جديد</h2>
        <form id="create-form" class="space-y-4">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label for="name" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">اسم الصنف</label>
                    <input type="text" id="name" name="name" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label for="description" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">وصف الصنف</label>
                    <textarea id="description" name="description" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required></textarea>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label for="category_id" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">فئة الصنف</label>
                    <select id="category_id" name="category_id" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                        <option value="">اختر فئة</option>
                        <!-- Categories will be populated here -->
                    </select>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label for="status" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">حالة الصنف</label>
                    <select id="status" name="status" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Populate categories select
        $.ajax({
            type: 'GET',
            url: '../backend/categories.php',
            success: function(data) {
                var categories = JSON.parse(data);
                $.each(categories, function(key, value) {
                    $('#category_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                });
            }
        });

        // Submit form via AJAX
        $('#create-form').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/الأصناف.php',
                data: formData,
                success: function(data) {
                    if (data == 'success') {
                        window.location.href = 'list_الأصناف.php';
                    } else {
                        alert('Error: ' + data);
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**Note:** This code assumes that you have the following files:

* `header.php`: includes the HTML header
* `navigation.php`: includes the navigation menu
* `footer.php`: includes the HTML footer
* `categories.php`: a backend script that returns a JSON array of categories
* `الأصناف.php`: a backend script that handles the form submission and adds a new category

Also, this code uses the jQuery library for AJAX requests and form submission. Make sure to include it in your HTML file.