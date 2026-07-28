**create_مخزون.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $quantity = trim($_POST['quantity']);
    $unit_price = trim($_POST['unit_price']);

    if (!empty($name) && !empty($quantity) && !empty($unit_price)) {
        // Insert data into database
        $sql = "INSERT INTO مخزون (name, quantity, unit_price) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sis", $name, $quantity, $unit_price);
        $stmt->execute();

        // Redirect back to list page
        header('Location: list_مخزون.php');
        exit;
    } else {
        $error = 'Please fill in all fields';
    }
}

// Include header
require_once '../includes/header.php';
?>

<!-- Create مخزون form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Create مخزون</h2>
    <form id="create-mkhzoon-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="quantity" class="block text-sm font-medium text-slate-900">Quantity:</label>
            <input type="number" id="quantity" name="quantity" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="unit_price" class="block text-sm font-medium text-slate-900">Unit Price:</label>
            <input type="number" id="unit_price" name="unit_price" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
        </div>
        <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Create</button>
    </form>
    <?php if (isset($error)) : ?>
        <p class="text-red-500 mt-2"><?= $error ?></p>
    <?php endif; ?>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>


**create_مخزون.js**
javascript
// Get form element
const form = document.getElementById('create-mkhzoon-form');

// Add event listener to form submission
form.addEventListener('submit', (e) => {
    e.preventDefault();

    // Get form data
    const formData = new FormData(form);

    // Send AJAX request to backend
    fetch('../backend/مخزون.php', {
        method: 'POST',
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.success) {
            // Redirect back to list page
            window.location.href = 'list_مخزون.php';
        } else {
            // Display error message
            const errorElement = document.createElement('p');
            errorElement.textContent = data.error;
            errorElement.classList.add('text-red-500', 'mt-2');
            form.parentNode.appendChild(errorElement);
        }
    })
    .catch((error) => console.error(error));
});


**Note:** Make sure to replace `../backend/مخزون.php` with the actual URL of your backend script that handles the form submission. Also, update the `list_مخزون.php` URL to match the actual URL of your list page.