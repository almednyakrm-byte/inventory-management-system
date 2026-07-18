**edit_المخزون.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$url = '../backend/المخزون.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found';
    exit;
}

// Set page title and mod slug
$page_title = 'Edit المخزون';
$mod_slug = 'المخزون';

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= $page_title ?></h1>
    <form id="edit-form" class="bg-white rounded-lg shadow-md p-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['name'] ?>">
            </div>
            <div>
                <label for="quantity" class="block text-sm font-medium text-slate-900">Quantity:</label>
                <input type="number" id="quantity" name="quantity" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['quantity'] ?>">
            </div>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Update</button>
    </form>
</div>

<script>
    // Fetch existing record details via GET
    fetch('../backend/المخزون.php?id=<?= $id ?>')
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('quantity').value = data.quantity;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-form').addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(document.getElementById('edit-form'));
        fetch('../backend/المخزون.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/المخزون.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'Invalid request'));
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Check if id exists in database
// Replace with your database query
if (!isset($id)) {
    echo json_encode(array('error' => 'Record not found'));
    exit;
}

// Get existing record details
// Replace with your database query
$data = array(
    'name' => 'Existing Name',
    'quantity' => 'Existing Quantity'
);

// Return data as JSON
echo json_encode($data);
?>


**header.php**

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <!-- Navigation bar -->
    <nav class="bg-slate-900 text-white p-4">
        <ul class="flex justify-between items-center">
            <li><a href="#" class="text-sm font-medium">Home</a></li>
            <li><a href="#" class="text-sm font-medium">About</a></li>
            <li><a href="#" class="text-sm font-medium">Contact</a></li>
        </ul>
    </nav>
    <!-- Main content -->
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
        <?= $content ?>
    </div>
</body>
</html>


**footer.php**

<footer class="bg-slate-900 text-white p-4">
    <p>&copy; 2023 <?= $page_title ?></p>
</footer>


**navigation.php**

<nav class="bg-slate-900 text-white p-4">
    <ul class="flex justify-between items-center">
        <li><a href="#" class="text-sm font-medium">Home</a></li>
        <li><a href="#" class="text-sm font-medium">About</a></li>
        <li><a href="#" class="text-sm font-medium">Contact</a></li>
    </ul>
</nav>