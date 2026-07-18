<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Module slug
$mod_slug = 'طلبات';

// Page title
$page_title = 'Create ' . $mod_slug;

// Include header
include 'header.php';
?>

<!-- Content -->
<div class="container mx-auto p-4 pt-6 mt-10">
    <h1 class="text-3xl text-slate-900 font-bold mb-4"><?= $page_title ?></h1>
    <form id="create-form" class="bg-white rounded shadow-md p-4">
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label for="customer_name" class="block text-sm text-slate-900 font-medium">Customer Name</label>
                <input type="text" id="customer_name" name="customer_name" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div>
                <label for="order_date" class="block text-sm text-slate-900 font-medium">Order Date</label>
                <input type="date" id="order_date" name="order_date" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div>
                <label for="total_amount" class="block text-sm text-slate-900 font-medium">Total Amount</label>
                <input type="number" id="total_amount" name="total_amount" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div>
                <label for="status" class="block text-sm text-slate-900 font-medium">Status</label>
                <select id="status" name="status" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div>
                <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create</button>
            </div>
        </div>
    </form>
</div>

<!-- JavaScript -->
<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/<?= $mod_slug ?>.php',
                data: $(this).serialize(),
                success: function(data) {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>