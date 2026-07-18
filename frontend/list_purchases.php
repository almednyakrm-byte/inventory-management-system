**list_purchases.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchases</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .bg-slate-900 {
            background-color: #1A1D23;
        }
        .text-indigo-500 {
            color: #6B7280;
        }
    </style>
</head>
<body class="bg-slate-900">
    <div class="container mx-auto p-4">
        <header class="bg-slate-900 p-4 flex justify-between items-center">
            <a href="index.php" class="text-indigo-500 hover:text-white">Back to Index</a>
            <div class="flex items-center">
                <p class="text-indigo-500 mr-2">Welcome, <?php echo $_SESSION['username']; ?></p>
                <a href="logout.php" class="text-indigo-500 hover:text-white">Logout</a>
            </div>
        </header>
        <main class="bg-slate-900 p-4">
            <h1 class="text-indigo-500 text-2xl mb-4">Purchases</h1>
            <div class="flex justify-between items-center mb-4">
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_purchases.php'">Add New Item</button>
                <input type="search" id="search" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Search...">
            </div>
            <table class="w-full border-collapse border border-slate-400">
                <thead>
                    <tr>
                        <th class="border border-slate-400 p-2">ID</th>
                        <th class="border border-slate-400 p-2">Name</th>
                        <th class="border border-slate-400 p-2">Price</th>
                        <th class="border border-slate-400 p-2">Actions</th>
                    </tr>
                </thead>
                <tbody id="purchases-list">
                    <?php
                    // Fetch list records from backend
                    $url = '../backend/purchases.php';
                    $search = $_GET['search'] ?? '';
                    $params = http_build_query(['search' => $search]);
                    $response = file_get_contents($url . '?' . $params);
                    $data = json_decode($response, true);
                    foreach ($data as $purchase) {
                        ?>
                        <tr>
                            <td class="border border-slate-400 p-2"><?php echo $purchase['id']; ?></td>
                            <td class="border border-slate-400 p-2"><?php echo $purchase['name']; ?></td>
                            <td class="border border-slate-400 p-2"><?php echo $purchase['price']; ?></td>
                            <td class="border border-slate-400 p-2 flex justify-between items-center">
                                <a href="edit_purchases.php?id=<?php echo $purchase['id']; ?>" class="text-indigo-500 hover:text-white">Edit</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deletePurchase(<?php echo $purchase['id']; ?>)">Delete</button>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', function() {
            const searchValue = this.value;
            const url = '../backend/purchases.php';
            const params = new URLSearchParams({ search: searchValue });
            fetch(url, { method: 'GET', params })
                .then(response => response.json())
                .then(data => {
                    const purchasesList = document.getElementById('purchases-list');
                    purchasesList.innerHTML = '';
                    data.forEach(purchase => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${purchase.id}</td>
                            <td>${purchase.name}</td>
                            <td>${purchase.price}</td>
                            <td class="flex justify-between items-center">
                                <a href="edit_purchases.php?id=${purchase.id}" class="text-indigo-500 hover:text-white">Edit</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deletePurchase(${purchase.id})">Delete</button>
                            </td>
                        `;
                        purchasesList.appendChild(row);
                    });
                });
        });

        function deletePurchase(id) {
            if (confirm('Are you sure you want to delete this purchase?')) {
                fetch('../backend/purchases.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Purchase deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error deleting purchase!');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

**backend/purchases.php**

<?php
// Database connection
$db = new PDO('sqlite:database.db');

// Search query
$search = $_GET['search'] ?? '';
$query = "SELECT * FROM purchases WHERE name LIKE :search OR price LIKE :search";
$stmt = $db->prepare($query);
$stmt->bindParam(':search', '%' . $search . '%');
$stmt->execute();

// Fetch and return data
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($data);

// Delete purchase
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = json_decode(file_get_contents('php://input'), true)['id'];
    $query = "DELETE FROM purchases WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo json_encode(['success' => true]);
}