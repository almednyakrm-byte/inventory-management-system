**edit_مخزونات.php**

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
$url = '../backend/مخزونات.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found';
    exit;
}

// Set page title and mod slug
$page_title = 'Edit مخزونات';
$mod_slug = 'مخزونات';

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6">
    <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= $page_title ?></h1>

    <form id="edit-form" class="bg-white rounded shadow-md p-4">
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['name'] ?>">
        </div>
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="quantity" class="block text-sm font-medium text-slate-900">Quantity:</label>
            <input type="number" id="quantity" name="quantity" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['quantity'] ?>">
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update</button>
    </form>
</div>

<script>
    // Fetch existing record details via GET
    fetch('../backend/مخزونات.php?id=<?= $id ?>')
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

        // Get form data
        const formData = new FormData(document.getElementById('edit-form'));

        // Send AJAX PUT request
        fetch('../backend/مخزونات.php', {
            method: 'PUT',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                // Redirect to list page
                window.location.href = 'list_<?= $mod_slug ?>.php';
            })
            .catch(error => console.error(error));
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/مخزونات.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    echo 'Error: ID not set';
    exit;
}

// Get id
$id = $_GET['id'];

// Check if id is numeric
if (!is_numeric($id)) {
    echo 'Error: ID is not numeric';
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get existing record details
$stmt = $conn->prepare("SELECT * FROM مخزونات WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch data
$data = $result->fetch_assoc();

// Close connection
$conn->close();

// Output data
echo json_encode($data);
?>


**backend/مخزونات.php (PUT request handler)**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    echo 'Error: ID not set';
    exit;
}

// Get id
$id = $_GET['id'];

// Check if id is numeric
if (!is_numeric($id)) {
    echo 'Error: ID is not numeric';
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'];
$quantity = $_POST['quantity'];

// Update record
$stmt = $conn->prepare("UPDATE مخزونات SET name = ?, quantity = ? WHERE id = ?");
$stmt->bind_param("ssi", $name, $quantity, $id);
$stmt->execute();

// Close connection
$conn->close();

// Output success message
echo 'Record updated successfully';
?>