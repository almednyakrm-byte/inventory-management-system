**edit_شحنات.php**

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
$existingRecord = json_decode(file_get_contents('../backend/شحنات.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Record not found';
    exit;
}

// Set page title
$pageTitle = 'Edit شحنات';

// Include header
include 'header.php';

?>

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= $pageTitle ?></h1>

    <form id="edit-shihnat-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
            <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $existingRecord['name'] ?>">
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
            <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?= $existingRecord['description'] ?></textarea>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update شحنات</button>
    </form>
</div>

<script>
    // Fetch existing record details via GET
    fetch('../backend/شحنات.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('description').value = data.description;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-shihnat-form').addEventListener('submit', function(event) {
        event.preventDefault();

        // Create AJAX PUT request
        fetch('../backend/شحنات.php', {
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
            // Redirect to list page on success
            window.location.href = 'list_<?= $mod_slug ?>.php';
        })
        .catch(error => console.error(error));
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**header.php**

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <div class="container mx-auto p-4">
        <nav class="bg-slate-900 text-white py-4">
            <ul class="flex justify-between items-center">
                <li class="mr-4"><a href="index.php" class="text-white hover:text-indigo-500">Home</a></li>
                <li class="mr-4"><a href="list_<?= $mod_slug ?>.php" class="text-white hover:text-indigo-500">List</a></li>
                <li class="mr-4"><a href="add_<?= $mod_slug ?>.php" class="text-white hover:text-indigo-500">Add</a></li>
                <li><a href="profile.php" class="text-white hover:text-indigo-500"><?= $_SESSION['username'] ?></a></li>
            </ul>
        </nav>
    </div>
    <div class="container mx-auto p-4">
        <?= $content ?>
    </div>
</body>
</html>


**footer.php**

<footer class="bg-slate-900 text-white py-4">
    <div class="container mx-auto p-4">
        <p>&copy; <?= date('Y') ?> <?= $_SESSION['username'] ?></p>
    </div>
</footer>


**backend/شحنات.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    echo 'Invalid request';
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get existing record details
$stmt = $conn->prepare("SELECT * FROM شحنات WHERE id = ?");
$stmt->bind_param("i", $_GET['id']);
$stmt->execute();
$result = $stmt->get_result();

// Fetch record details
$record = $result->fetch_assoc();

// Output record details as JSON
echo json_encode($record);

// Close connection
$conn->close();
?>


**backend/update_شحنات.php**

<?php
// Check if id is set
if (!isset($_POST['id'])) {
    echo 'Invalid request';
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update existing record
$stmt = $conn->prepare("UPDATE شحنات SET name = ?, description = ? WHERE id = ?");
$stmt->bind_param("ssi", $_POST['name'], $_POST['description'], $_POST['id']);
$stmt->execute();

// Close connection
$conn->close();

// Output success message
echo 'Record updated successfully';
?>