**create_صفقات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);

    if (empty($name) || empty($description) || empty($price)) {
        $error = 'Please fill in all fields.';
    } else {
        // Insert data into database
        $sql = "INSERT INTO صفقات (name, description, price) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $description, $price]);

        // Redirect back to list page
        header('Location: list_صفقات.php');
        exit;
    }
}

// Include header
require_once '../backend/header.php';

?>

<!-- Create new صفقات form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Create New صفقات</h2>
    <form id="create-form" method="POST">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-slate-900">Description:</label>
            <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
        </div>
        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-slate-900">Price:</label>
            <input type="number" id="price" name="price" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../backend/footer.php'; ?>


**create_صفقات.js**
javascript
// Get form element
const form = document.getElementById('create-form');

// Add event listener to form submission
form.addEventListener('submit', (e) => {
    e.preventDefault();

    // Get form data
    const formData = new FormData(form);

    // Send AJAX request to backend
    fetch('../backend/صفقات.php', {
        method: 'POST',
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.success) {
            // Redirect back to list page
            window.location.href = 'list_صفقات.php';
        } else {
            // Display error message
            alert(data.error);
        }
    })
    .catch((error) => {
        console.error(error);
    });
});


**backend/صفقات.php**

<?php
// Include database connection
require_once '../db.php';

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);

    if (empty($name) || empty($description) || empty($price)) {
        echo json_encode(['success' => false, 'error' => 'Please fill in all fields.']);
        exit;
    }

    // Insert data into database
    $sql = "INSERT INTO صفقات (name, description, price) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $description, $price]);

    // Redirect back to list page
    echo json_encode(['success' => true]);
    exit;
}

// Handle errors
echo json_encode(['success' => false, 'error' => 'Invalid request.']);
exit;