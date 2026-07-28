<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #2c3e50, #1a1d23);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s;
        }
        
        .glassmorphic {
            background: linear-gradient(90deg, #2c3e50 0%, #1a1d23 100%);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .glassmorphic::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #2c3e50 0%, #1a1d23 100%);
            filter: blur(10px);
            z-index: -1;
        }
        
        .glassmorphic::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #2c3e50 0%, #1a1d23 100%);
            filter: blur(20px);
            opacity: 0.5;
            z-index: -2;
        }
    </style>
</head>
<body class="h-screen bg-gray-900">
    <div class="flex justify-center items-center h-screen">
        <div class="glassmorphic p-10 bg-white rounded-lg shadow-2xl w-96">
            <h1 class="text-3xl font-bold text-slate-900 mb-5">Login</h1>
            <form id="login-form">
                <div class="mb-4">
                    <label for="username" class="block text-slate-900 text-sm font-bold mb-2">Username</label>
                    <input type="text" id="username" name="username" class="block w-full p-2 pl-10 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-slate-900 text-sm font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password" class="block w-full p-2 pl-10 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Password" required>
                </div>
                <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Login</button>
            </form>
            <p class="text-sm text-gray-600 mt-2">Don't have an account? <a href="register.php" class="text-indigo-500 hover:text-indigo-700">Register</a></p>
        </div>
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
                    alert('Login successful!');
                    window.location.href = 'dashboard.php';
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        });
    </script>
</body>
</html>


This code creates a premium-looking login page with a glassmorphic layout, gradients, and a form for username and password input. It uses the Tailwind CSS CDN for styling and includes a direct link to the register page. The form submission is handled using AJAX with the Fetch API, and the response or error is handled dynamically using JavaScript. The standard HTML input pattern validator is included to support Arabic and Latin characters.