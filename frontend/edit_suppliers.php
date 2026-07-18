<?php
// Session validation
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get supplier ID from URL
$supplier_id = $_GET['id'];

// Include database connection
include '../backend/db.php';

// Check if supplier exists
$query = "SELECT * FROM suppliers WHERE id = '$supplier_id'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0) {
    header('Location: list_suppliers.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Supplier</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-6 bg-slate-900 rounded-lg shadow-md">
        <h2 class="text-2xl text-indigo-500 font-bold mb-4">Edit Supplier</h2>
        <form id="edit-supplier-form">
            <div class="mb-4">
                <label for="name" class="block text-indigo-500 text-sm font-bold mb-2">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 bg-slate-100 rounded-lg shadow-sm">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-indigo-500 text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" class="block w-full p-2 bg-slate-100 rounded-lg shadow-sm">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-indigo-500 text-sm font-bold mb-2">Phone</label>
                <input type="text" id="phone" name="phone" class="block w-full p-2 bg-slate-100 rounded-lg shadow-sm">
            </div>
            <div class="mb-4">
                <label for="address" class="block text-indigo-500 text-sm font-bold mb-2">Address</label>
                <textarea id="address" name="address" class="block w-full p-2 bg-slate-100 rounded-lg shadow-sm"></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Update Supplier</button>
        </form>
    </div>

    <script>
        // Fetch existing supplier details
        fetch('../backend/suppliers.php?id=<?php echo $supplier_id; ?>')
            .then(response => response.json())
            .then(data => {
                document.getElementById('name').value = data.name;
                document.getElementById('email').value = data.email;
                document.getElementById('phone').value = data.phone;
                document.getElementById('address').value = data.address;
            });

        // Submit form using AJAX
        document.getElementById('edit-supplier-form').addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            fetch('../backend/suppliers.php', {
                method: 'PUT',
                body: JSON.stringify({
                    id: <?php echo $supplier_id; ?>,
                    name: formData.get('name'),
                    email: formData.get('email'),
                    phone: formData.get('phone'),
                    address: formData.get('address')
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_suppliers.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>