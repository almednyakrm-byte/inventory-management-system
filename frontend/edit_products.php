<?php
// edit_products.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_products.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-slate-900 rounded-md shadow-md">
        <h2 class="text-2xl text-indigo-500 font-bold mb-4">Edit Product</h2>
        <form id="edit-product-form">
            <div class="mb-4">
                <label for="name" class="block text-sm text-indigo-500 font-bold mb-2">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 bg-slate-100 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm text-indigo-500 font-bold mb-2">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 bg-slate-100 rounded-md shadow-sm"></textarea>
            </div>
            <div class="mb-4">
                <label for="price" class="block text-sm text-indigo-500 font-bold mb-2">Price</label>
                <input type="number" id="price" name="price" class="block w-full p-2 bg-slate-100 rounded-md shadow-sm">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">Update Product</button>
        </form>
    </div>

    <script>
        const id = <?php echo $id; ?>;
        const form = document.getElementById('edit-product-form');

        // Fetch existing record details
        fetch(`../backend/products.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('name').value = data.name;
                document.getElementById('description').value = data.description;
                document.getElementById('price').value = data.price;
            });

        // Submit form using AJAX PUT request
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch(`../backend/products.php`, {
                method: 'PUT',
                body: JSON.stringify({
                    id: id,
                    name: formData.get('name'),
                    description: formData.get('description'),
                    price: formData.get('price')
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_products.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>