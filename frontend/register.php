<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-size: 18px;
            color: #666;
        }
        .form-group input {
            width: 100%;
            height: 40px;
            padding: 10px;
            font-size: 18px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group input:focus {
            border-color: #aaa;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .btn {
            width: 100%;
            height: 40px;
            padding: 10px;
            font-size: 18px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #444;
        }
        .error {
            color: #f00;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="title text-center">Register</h2>
        <form id="register-form">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                <div class="error" id="username-error"></div>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
                <div class="error" id="email-error"></div>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <div class="error" id="password-error"></div>
            </div>
            <button class="btn" type="submit">Register</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#register-form').submit(function(e) {
                e.preventDefault();
                var username = $('#username').val();
                var email = $('#email').val();
                var password = $('#password').val();
                var usernameError = $('#username-error');
                var emailError = $('#email-error');
                var passwordError = $('#password-error');
                usernameError.text('');
                emailError.text('');
                passwordError.text('');
                if (!username.match(pattern)) {
                    usernameError.text('Invalid username. Only letters, numbers, and spaces are allowed.');
                    return false;
                }
                if (!email.match(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/)) {
                    emailError.text('Invalid email address.');
                    return false;
                }
                if (password.length < 8) {
                    passwordError.text('Password must be at least 8 characters long.');
                    return false;
                }
                $.ajax({
                    type: 'POST',
                    url: '../backend/auth.php?action=register',
                    data: {
                        username: username,
                        email: email,
                        password: password
                    },
                    success: function(data) {
                        if (data == 'success') {
                            alert('Registration successful. Please login to continue.');
                            window.location.href = 'login.php';
                        } else {
                            alert('Registration failed. Please try again.');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


This code uses the Tailwind CSS CDN to create a premium-looking frontend page for user registration. The form fields are validated using JavaScript and the `pattern` attribute to ensure that the username and email address are in the correct format. The password is also validated to ensure it is at least 8 characters long. The form data is submitted to the `auth.php` script via AJAX, which handles the registration process. If the registration is successful, the user is redirected to the login page.