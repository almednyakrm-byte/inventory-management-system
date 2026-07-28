**edit_إنتاج.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$url = '../backend/إنتاج.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found';
    exit;
}

// Set page title and mod slug
$page_title = 'Edit إنتاج';
$mod_slug = 'إنتاج';

// Include header and navigation
include 'header.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12 2xl:p-12">
    <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= $page_title ?></h1>

    <form id="edit-form" class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-8 2xl:p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">إسم</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['name'] ?>">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-900">وصف</label>
                <textarea id="description" name="description" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" rows="4"><?= $data['description'] ?></textarea>
            </div>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">تعديل</button>
    </form>
</div>

<script>
    // Fetch existing record details via GET
    fetch('../backend/إنتاج.php?id=<?= $id ?>')
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('description').value = data.description;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-form').addEventListener('submit', function(event) {
        event.preventDefault();

        // Get form data
        const formData = new FormData(this);

        // Send AJAX PUT request
        fetch('../backend/إنتاج.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to list page
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                } else {
                    console.error(data.message);
                }
            })
            .catch(error => console.error(error));
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/إنتاج.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID not set']);
    exit;
}

// Get ID
$id = $_GET['id'];

// Check if ID exists in database
// Replace with your database query
if (!isset($id)) {
    echo json_encode(['error' => 'Record not found']);
    exit;
}

// Get record details
// Replace with your database query
$data = [
    'name' => 'إسم',
    'description' => 'وصف'
];

echo json_encode($data);
?>


Note: This code assumes you have a `header.php` and `footer.php` file that includes the HTML for the page header and footer, respectively. You will need to replace the database queries in the `backend/إنتاج.php` file with your actual database code.