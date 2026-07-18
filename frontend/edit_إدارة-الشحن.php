**edit_إدارة-الشحن.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/إدارة-الشحن.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Error: Record not found.';
    exit;
}

// Set page title and mod slug
$pageTitle = 'Edit إدارة الشحن';
$modSlug = 'إدارة-الشحن';

// Include header and navigation
include 'header.php';
?>

<!-- Page content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <h1 class="text-3xl font-bold mb-4 text-slate-900"><?= $pageTitle ?></h1>

    <!-- Form -->
    <form id="edit-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label for="name" class="block text-slate-700 text-sm font-bold mb-2">إسم الشحن</label>
            <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $existingRecord['name'] ?>">
        </div>

        <div class="mb-4">
            <label for="description" class="block text-slate-700 text-sm font-bold mb-2">وصف الشحن</label>
            <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:shadow-outline"><?= $existingRecord['description'] ?></textarea>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Save Changes</button>
    </form>
</div>

<!-- JavaScript -->
<script>
    // Fetch existing record details via GET
    fetch('../backend/إدارة-الشحن.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('description').value = data.description;
        })
        .catch(error => console.error(error));

    // Form submission handler
    document.getElementById('edit-form').addEventListener('submit', event => {
        event.preventDefault();

        // Send AJAX PUT request
        fetch('../backend/إدارة-الشحن.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: <?= $id ?>,
                name: document.getElementById('name').value,
                description: document.getElementById('description').value
            })
        })
        .then(response => response.json())
        .then(data => {
            // Redirect to list page
            window.location.href = 'list_<?= $modSlug ?>.php';
        })
        .catch(error => console.error(error));
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/إدارة-شحن.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'ID not set'));
    exit;
}

// Get ID
$id = $_GET['id'];

// Check if record exists
$record = get_record($id);

// If record exists, return JSON data
if ($record) {
    echo json_encode($record);
} else {
    echo json_encode(array('error' => 'Record not found'));
}

// Function to get record
function get_record($id) {
    // Database query to get record
    $query = "SELECT * FROM إدارة_الشحن WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    $record = mysqli_fetch_assoc($result);
    return $record;
}
?>


**Note:** This code assumes you have a `get_record` function in your backend script that retrieves the record from the database based on the provided ID. You should replace this function with your actual database query. Additionally, this code uses the `mysqli` extension for database interactions, which is deprecated in PHP 7.4 and removed in PHP 8.0. You should consider using `PDO` or `mysqli` with prepared statements for better security.