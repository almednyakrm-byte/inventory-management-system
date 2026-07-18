**create_شحنات.php**

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

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $quantity = trim($_POST['quantity']);

    // Check for empty fields
    if (empty($name) || empty($description) || empty($price) || empty($quantity)) {
        $error = 'Please fill in all fields.';
    } else {
        // Insert data into database
        $sql = "INSERT INTO شحنات (name, description, price, quantity) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssds', $name, $description, $price, $quantity);
        $stmt->execute();

        // Redirect back to list page
        header('Location: list_شحنات.php');
        exit;
    }
}

// Include header
require_once '../backend/header.php';

?>

<!-- Create شحنات form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-slate-900 font-bold text-lg mb-4">Create شحنات</h2>
    <form id="create-shhant-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-slate-900 text-sm font-bold mb-2">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 text-slate-900 border border-slate-300 rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-slate-900 text-sm font-bold mb-2">Description:</label>
            <textarea id="description" name="description" class="block w-full p-2 text-slate-900 border border-slate-300 rounded-lg" required></textarea>
        </div>
        <div class="mb-4">
            <label for="price" class="block text-slate-900 text-sm font-bold mb-2">Price:</label>
            <input type="number" id="price" name="price" class="block w-full p-2 text-slate-900 border border-slate-300 rounded-lg" required>
        </div>
        <div class="mb-4">
            <label for="quantity" class="block text-slate-900 text-sm font-bold mb-2">Quantity:</label>
            <input type="number" id="quantity" name="quantity" class="block w-full p-2 text-slate-900 border border-slate-300 rounded-lg" required>
        </div>
        <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create شحنات</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../backend/footer.php'; ?>

<script>
    // AJAX form submission
    document.getElementById('create-shhant-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        fetch('../backend/شحنات.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_شحنات.php';
            } else {
                alert(data.error);
            }
        })
        .catch(error => console.error(error));
    });
</script>


**backend/شحنات.php**

<?php
// Include database connection
require_once '../db.php';

// Check if form data has been sent
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price']) && isset($_POST['quantity'])) {
    // Insert data into database
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $quantity = trim($_POST['quantity']);

    $sql = "INSERT INTO شحنات (name, description, price, quantity) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssds', $name, $description, $price, $quantity);
    $stmt->execute();

    // Return success message
    echo json_encode(array('success' => true));
} else {
    // Return error message
    echo json_encode(array('error' => 'Invalid request.'));
}
?>