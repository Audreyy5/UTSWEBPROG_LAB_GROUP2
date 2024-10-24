<?php
session_start();
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login_input = htmlspecialchars($_POST['login_input'], ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'];

    if (empty($login_input) || empty($password)) {
        echo "Username/email and password are required!";
        exit();
    }

    if (filter_var($login_input, FILTER_VALIDATE_EMAIL)) {
        $sql = "SELECT * FROM users WHERE email = ?";
    } else {
        $sql = "SELECT * FROM users WHERE username = ?";
    }

    $stmt = $db->prepare($sql);
    $stmt->execute([$login_input]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        echo "<script>
                alert('Welcome, " . $user['username'] . "!');
                window.location.href = 'dashboard.php';
              </script>";
        exit();
    } else {
        echo "<strong class='text-red-500'>Invalid username/email or password!</strong>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            animation: backgroundChange 15s ease infinite;
        }

        @keyframes backgroundChange {
            0% {
                background-color: #FEF2F2;
            }

            25% {
                background-color: #fffbeb;
            }

            50% {
                background-color: #FEF3C7;
            }

            75% {
                background-color: #FFFBEB;
            }

            100% {
                background-color: #FEF2F2;
            }
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 10px;
            cursor: pointer;
        }

        .poppins-regular {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
    </style>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=arrow_back" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=favorite" />
</head>

<body class="p-6 bg-gray-100 poppins-regular">
    <a href="profil.php" class="absolute top-5 right-5 text-[#991B1B] text-5xl hover:animate-spin transition duration-300">
        <span class="material-symbols-outlined text-5xl">
            favorite
        </span>
    </a>
    <a href="firstpage.php" class="text-blue hover:text-blue-700 ml-6 font-bold text-lg hover:underline">&larr;</a>
    <h1 class="text-3xl text-center font-bold text-[#1E3A8A] mt-20">Hello, Welcome Back! We Miss You! </h1>
    <div class="max-w-md mx-auto mt-8 bg-white shadow-md rounded px-8 pt-6 pb-8">
        <h1 class="text-3xl font-bold mb-6 text-center">Login</h1>
        <form action="login.php" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="login_input">Username/Email</label>
                <input type="text" name="login_input" id="login_input" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div class="mb-4 relative">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                <input type="password" name="password" id="password" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                <span class="toggle-password pt-6" onclick="togglePassword()">🙈</span>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-red-500 hover:bg-red-200 text-white font-bold py-2 px-4 mt-4 rounded focus:outline-none focus:shadow-outline">
                    Login
                </button>
                <a href="forgot_password.php" class="inline-block align-baseline font-bold mt-4 text-sm text-blue-500 hover:text-blue-800">
                    Forgot Password?
                </a>
            </div>
        </form>
        <p class="mt-6 text-center text-gray-700">Don't have an account?
            <a href="register.php" class="text-blue-500 hover:text-blue-800 font-bold">Register here</a>
        </p>
    </div>

    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var togglePassword = document.querySelector(".toggle-password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                togglePassword.textContent = "🙉";
            } else {
                passwordField.type = "password";
                togglePassword.textContent = "🙈";
            }
        }
    </script>
</body>

</html>