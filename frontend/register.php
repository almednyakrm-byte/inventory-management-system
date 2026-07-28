<!-- register.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen">
    <div class="flex justify-center items-center h-full">
        <div class="bg-white p-8 rounded-lg shadow-md w-1/2">
            <h1 class="text-3xl text-slate-900 font-bold mb-4">Register</h1>
            <form id="register-form">
                <div class="mb-4">
                    <label for="username" class="block text-slate-900 text-sm font-bold mb-2">Username</label>
                    <input type="text" id="username" name="username" class="block w-full p-2 pl-10 text-sm text-gray-700 rounded-lg bg-gray-100 border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+">
                    <p id="username-error" class="text-red-500 hidden"></p>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-slate-900 text-sm font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" class="block w-full p-2 pl-10 text-sm text-gray-700 rounded-lg bg-gray-100 border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Email">
                    <p id="email-error" class="text-red-500 hidden"></p>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-slate-900 text-sm font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password" class="block w-full p-2 pl-10 text-sm text-gray-700 rounded-lg bg-gray-100 border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Password" pattern="[A-Za-z\u0600-\u06FF0-9\s]+">
                    <p id="password-error" class="text-red-500 hidden"></p>
                </div>
                <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Register</button>
            </form>
        </div>
    </div>

    <script>
        const form = document.getElementById('register-form');
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            const usernameError = document.getElementById('username-error');
            const emailError = document.getElementById('email-error');
            const passwordError = document.getElementById('password-error');

            if (username === '') {
                usernameError.textContent = 'Username is required';
                usernameError.classList.remove('hidden');
            } else if (!username.match(pattern)) {
                usernameError.textContent = 'Invalid username';
                usernameError.classList.remove('hidden');
            } else {
                usernameError.classList.add('hidden');
            }

            if (email === '') {
                emailError.textContent = 'Email is required';
                emailError.classList.remove('hidden');
            } else if (!email.match(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/)) {
                emailError.textContent = 'Invalid email';
                emailError.classList.remove('hidden');
            } else {
                emailError.classList.add('hidden');
            }

            if (password === '') {
                passwordError.textContent = 'Password is required';
                passwordError.classList.remove('hidden');
            } else if (!password.match(pattern)) {
                passwordError.textContent = 'Invalid password';
                passwordError.classList.remove('hidden');
            } else {
                passwordError.classList.add('hidden');
            }

            if (usernameError.classList.contains('hidden') && emailError.classList.contains('hidden') && passwordError.classList.contains('hidden')) {
                fetch('../backend/auth.php?action=register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        username,
                        email,
                        password
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Registration successful');
                        window.location.href = 'login.php';
                    } else {
                        alert('Registration failed');
                    }
                })
                .catch(error => console.error(error));
            }
        });
    </script>
</body>
</html>


Please note that you need to replace `backend/auth.php` with your actual backend file path. Also, you need to create a backend file `auth.php` to handle the registration request. 

Here's a simple example of how you can handle the registration request in `auth.php`:


<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Your database connection code here

    // Insert data into database
    $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>