**create_المخازن.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <h1 class="text-3xl font-bold text-slate-900 mb-4">Create New مخازن</h1>

    <form id="create-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label for="name" class="block text-slate-900 text-sm font-bold mb-2">Name</label>
            <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-900 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-slate-900 text-sm font-bold mb-2">Description</label>
            <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-900 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
        </div>

        <div class="mb-4">
            <label for="location" class="block text-slate-900 text-sm font-bold mb-2">Location</label>
            <input type="text" id="location" name="location" class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-900 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label for="capacity" class="block text-slate-900 text-sm font-bold mb-2">Capacity</label>
            <input type="number" id="capacity" name="capacity" class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-900 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Create</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/المخازن.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_المخازن.php';
                    } else {
                        alert('Error creating new مخازن');
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**المخازن.php (backend)**

<?php
// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Connect to database
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

// Check connection
if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

// Get form data
$name = $_POST['name'];
$description = $_POST['description'];
$location = $_POST['location'];
$capacity = $_POST['capacity'];

// Insert data into database
$sql = "INSERT INTO مخازن (name, description, location, capacity) VALUES ('$name', '$description', '$location', '$capacity')";
if (mysqli_query($conn, $sql)) {
    echo 'success';
} else {
    echo 'Error creating new مخازن';
}

// Close connection
mysqli_close($conn);
?>