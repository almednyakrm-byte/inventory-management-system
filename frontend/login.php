<!-- login.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(90deg, #3b3f54 0, #3b3f54 100%), linear-gradient(180deg, #3b3f54 0, #3b3f54 100%);
            background-size: 40px 40px, 40px 40px;
            background-position: 0 0, 20px 20px;
            background-repeat: repeat;
        }
        .glassmorphic {
            background: linear-gradient(90deg, #3b3f54 0, #3b3f54 100%), linear-gradient(180deg, #3b3f54 0, #3b3f54 100%);
            background-size: 40px 40px, 40px 40px;
            background-position: 0 0, 20px 20px;
            background-repeat: repeat;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body class="bg-gray-100 h-screen flex justify-center items-center">
    <div class="glassmorphic w-96 p-10 bg-slate-900 rounded-lg shadow-md">
        <h2 class="text-3xl text-center text-indigo-500 mb-5">Login</h2>
        <form id="login-form">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" class="block w-full p-2 mt-1 text-gray-700 border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                <div id="username-error" class="text-red-500 text-sm mt-1"></div>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="block w-full p-2 mt-1 text-gray-700 border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                <div id="password-error" class="text-red-500 text-sm mt-1"></div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Login</button>
        </form>
        <p class="text-center text-gray-500 mt-5">Don't have an account? <a href="register.php" class="text-indigo-500 hover:text-indigo-700">Register</a></p>
    </div>

    <script>
        const form = document.getElementById('login-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            try {
                const response = await fetch('../backend/auth.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username, password })
                });
                const data = await response.json();
                if (data.success) {
                    window.location.href = 'dashboard.php';
                } else {
                    document.getElementById('username-error').textContent = data.username_error;
                    document.getElementById('password-error').textContent = data.password_error;
                }
            } catch (error) {
                console.error(error);
                alert('Error logging in. Please try again later.');
            }
        });
    </script>
</body>
</html>


This code uses Tailwind CSS to create a premium-looking login page with a glassmorphic layout and gradients. It includes a form for username and password input, with validation rules using standard HTML input pattern attributes. The form is submitted using AJAX with the Fetch API, and the response is handled dynamically using JavaScript. The code also includes a link to the register page and handles errors and success messages accordingly.