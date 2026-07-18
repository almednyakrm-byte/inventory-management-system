**edit_purchases.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get purchase ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/purchases.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Set form fields
$purchase_id = $data['id'];
$purchase_date = $data['purchase_date'];
$purchase_total = $data['purchase_total'];
$purchase_description = $data['purchase_description'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Purchase</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-slate-900 text-lg font-bold mb-4">Edit Purchase</h2>
        <form id="edit-purchase-form">
            <div class="mb-4">
                <label for="purchase_date" class="block text-slate-900 text-sm font-bold mb-2">Purchase Date:</label>
                <input type="date" id="purchase_date" name="purchase_date" class="w-full p-2 text-slate-900 border border-slate-300 rounded-md" value="<?= $purchase_date ?>">
            </div>
            <div class="mb-4">
                <label for="purchase_total" class="block text-slate-900 text-sm font-bold mb-2">Purchase Total:</label>
                <input type="number" id="purchase_total" name="purchase_total" class="w-full p-2 text-slate-900 border border-slate-300 rounded-md" value="<?= $purchase_total ?>">
            </div>
            <div class="mb-4">
                <label for="purchase_description" class="block text-slate-900 text-sm font-bold mb-2">Purchase Description:</label>
                <textarea id="purchase_description" name="purchase_description" class="w-full p-2 text-slate-900 border border-slate-300 rounded-md" rows="4"><?= $purchase_description ?></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">Update Purchase</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-purchase-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/purchases.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_purchases.php';
                        } else {
                            alert('Error updating purchase');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**purchases.php (backend)**

<?php
// Check if purchase ID is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Fetch existing record details
    $query = "SELECT * FROM purchases WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
    echo json_encode($data);
} else {
    // Handle invalid request
    echo json_encode(array('error' => 'Invalid request'));
}
?>


**purchases.php (backend) - update logic**

<?php
// Check if purchase ID is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Fetch existing record details
    $query = "SELECT * FROM purchases WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    // Update record
    $purchase_date = $_POST['purchase_date'];
    $purchase_total = $_POST['purchase_total'];
    $purchase_description = $_POST['purchase_description'];

    $query = "UPDATE purchases SET purchase_date = '$purchase_date', purchase_total = '$purchase_total', purchase_description = '$purchase_description' WHERE id = '$id'";
    $result = mysqli_query($conn, $query);

    // Return success or error message
    if ($result) {
        echo json_encode(array('success' => 'Purchase updated successfully'));
    } else {
        echo json_encode(array('error' => 'Error updating purchase'));
    }
} else {
    // Handle invalid request
    echo json_encode(array('error' => 'Invalid request'));
}
?>