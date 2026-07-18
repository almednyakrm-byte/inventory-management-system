**edit_مخزون.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/مخزون.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Record not found';
    exit;
}

// Set page title and mod slug
$pageTitle = 'Edit مخزون';
$modSlug = 'مخزون';

// Include header
include 'header.php';

?>

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= $pageTitle ?></h1>

    <form id="edit-mkhzoun-form" class="bg-white rounded-lg shadow-md p-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['name'] ?>">
            </div>
            <div>
                <label for="quantity" class="block text-sm font-medium text-slate-900">Quantity:</label>
                <input type="number" id="quantity" name="quantity" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['quantity'] ?>">
            </div>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">Save Changes</button>
    </form>
</div>

<script>
    // Fetch existing record details via GET
    fetch('../backend/مخزون.php?id=<?= $id ?>')
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('quantity').value = data.quantity;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-mkhzoun-form').addEventListener('submit', event => {
        event.preventDefault();

        // Get form data
        const formData = new FormData(event.target);

        // Send AJAX PUT request
        fetch('../backend/مخزون.php', {
            method: 'PUT',
            body: formData,
            headers: {
                'X-CSRF-Token': '<?= $_SESSION['csrf_token'] ?>'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to list page
                    window.location.href = 'list_' + '<?= $modSlug ?>' + '.php';
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


**header.php**

<?php
// Include header HTML
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <header class="bg-slate-900 py-4">
        <nav class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-white font-bold text-2xl">Logo</a>
            <ul class="flex items-center space-x-4">
                <li><a href="#" class="text-white hover:text-indigo-500">Home</a></li>
                <li><a href="#" class="text-white hover:text-indigo-500">About</a></li>
                <li><a href="#" class="text-white hover:text-indigo-500">Contact</a></li>
            </ul>
        </nav>
    </header>
    <main class="container mx-auto p-4">
        <?= $content ?>
    </main>
</body>
</html>


**footer.php**

<?php
// Include footer HTML
?>
<footer class="bg-slate-900 py-4">
    <div class="container mx-auto text-center text-white">
        &copy; 2023 Company Name
    </div>
</footer>


**backend/مخزون.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit;
}

// Get id
$id = $_GET['id'];

// Check if id is valid
if (!is_numeric($id)) {
    http_response_code(400);
    exit;
}

// Get record from database
$record = get_record_from_database($id);

// Return record as JSON
echo json_encode($record);

function get_record_from_database($id) {
    // Replace with your actual database query
    return array(
        'id' => $id,
        'name' => 'Record Name',
        'quantity' => 10
    );
}
?>


Note: This code assumes you have a `get_record_from_database` function that retrieves the record from your database. You should replace this function with your actual database query. Additionally, you should implement CSRF protection and validate user input in your backend code.