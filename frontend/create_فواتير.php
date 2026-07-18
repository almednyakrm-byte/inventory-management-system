**create_فواتير.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-8 xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة فاتورة جديدة</h2>
        <form id="create-fatura-form">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <div class="mb-4">
                    <label for="client_name" class="block text-slate-900 text-sm font-bold mb-2">اسم العميل</label>
                    <input type="text" id="client_name" name="client_name" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="mb-4">
                    <label for="invoice_date" class="block text-slate-900 text-sm font-bold mb-2">تاريخ الفاتورة</label>
                    <input type="date" id="invoice_date" name="invoice_date" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="mb-4">
                    <label for="invoice_number" class="block text-slate-900 text-sm font-bold mb-2">رقم الفاتورة</label>
                    <input type="text" id="invoice_number" name="invoice_number" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="mb-4">
                    <label for="total_amount" class="block text-slate-900 text-sm font-bold mb-2">المبلغ الإجمالي</label>
                    <input type="number" id="total_amount" name="total_amount" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="mb-4">
                    <label for="payment_status" class="block text-slate-900 text-sm font-bold mb-2">حالة الدفع</label>
                    <select id="payment_status" name="payment_status" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">اختر حالة الدفع</option>
                        <option value="مدفوع">مدفوع</option>
                        <option value="غير مدفوع">غير مدفوع</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">حفظ</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-fatura-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/فواتير.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_فواتير.php';
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
include 'footer.php';
?>


**Note:** This code assumes that you have jQuery and Bootstrap installed in your project. Also, make sure to replace `../backend/فواتير.php` with the actual URL of your backend script.