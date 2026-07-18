**create_مخازن.php**

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

// Create a new record
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $capacity = $_POST['capacity'];

    // Validate input
    if (empty($name) || empty($description) || empty($capacity)) {
        $error = 'All fields are required';
    } else {
        // Insert record into database
        $query = "INSERT INTO مخازن (name, description, capacity) VALUES ('$name', '$description', '$capacity')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Redirect back to list page
            header('Location: list_مخازن.php');
            exit;
        } else {
            $error = 'Failed to create record';
        }
    }
}

// Include header
require_once '../includes/header.php';

// Include premium Tailwind UI form
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12 2xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-8 2xl:p-8">
        <h2 class="text-slate-900 font-bold text-lg mb-4">Create New مخازن</h2>
        <form id="create-form" method="POST">
            <div class="mb-4">
                <label for="name" class="block text-slate-900 text-sm font-bold mb-2">Name:</label>
                <input type="text" id="name" name="name" class="bg-gray-100 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-slate-900 text-sm font-bold mb-2">Description:</label>
                <textarea id="description" name="description" class="bg-gray-100 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 h-20" required></textarea>
            </div>
            <div class="mb-4">
                <label for="capacity" class="block text-slate-900 text-sm font-bold mb-2">Capacity:</label>
                <input type="number" id="capacity" name="capacity" class="bg-gray-100 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
            </div>
            <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create</button>
        </form>
        <?php if (isset($error)) : ?>
            <p class="text-red-500 text-sm mt-2"><?= $error ?></p>
        <?php endif; ?>
    </div>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/مخازن.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_مخازن.php';
                    } else {
                        alert('Failed to create record');
                    }
                }
            });
        });
    });
</script>


**Note:** This code assumes you have a `database.php` file that connects to your database and a `header.php` and `footer.php` files that include the necessary HTML for the header and footer of your page. You will need to modify the code to fit your specific needs. Additionally, this code uses the `mysqli` extension to interact with the database, which is deprecated in PHP 7.0 and removed in PHP 8.0. You should consider using the `PDO` extension or `mysqli` with prepared statements instead.