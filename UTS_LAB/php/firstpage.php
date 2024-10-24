<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulist</title>
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined">

</head>

<body class="poppins-regular">

    <header class="flex justify-between items-center p-6 bg-white shadow-md">
        <img class="w-32 h-20" src="../photos/logo.png"></img>
        <div class="space-x-2 md:space-x-4">
            <a href="login.php">
                <button class="bg-red-500 text-white px-3 py-2 md:px-4 
            rounded hover:bg-red-300">Sign In</button></a>
            <a href="register.php">
                <button class="bg-red-500 text-white px-3 py-2 md:px-4 rounded hover:bg-red-300">Register</button></a>
        </div>
    </header>

    <main class="text-center mt-10 px-4">
        <h1 class="text-5xl text-center italic font-bold text-blue-900 mt-16">WELCOME TO ULIST ! </h1>
        <h1 class="text-2xl md:text-3xl mt-16 font-bold text-red-800 italic mb-2 animate-bounce">"one way to make your daily life easier, faster, and quicker!"</h1>
        <h2 class="text-xl md:text-2xl font-bold text-red-500 mt-10 mb-7">What can you do ? Anything that you need !!</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10 max-w-4xl mx-auto">
        <div class="bg-white p-6 shadow-lg rounded-lg">
                <span class="material-icons-outlined">
                    inventory
                </span>
                <p>Organizing</p>
            </div>
            <div class="bg-white p-6 shadow-lg rounded-lg">
                <span class="material-icons-outlined">
                    draw
                </span>
                <p>Writing</p>
            </div>
            <div class="bg-white p-6 shadow-lg rounded-lg">
                <span class="material-icons-outlined">
                    checklist
                </span>
                <p>Scheduling</p>
            </div>
        </div>

        <h3 class="text-xl md:text-2xl font-bold text-red-500 mb-5">What do they say about this website ?</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-4xl mx-auto">
            <div class="bg-white p-4 shadow-md rounded-lg">
                <p>"This website really helped me a lot! Thank you"</p>
                <p class="font-bold">- Reya, University of Cambridge</p>
            </div>
            <div class="bg-white p-4 shadow-md rounded-lg">
                <p>"Amazing website that helps our daily uni lives!!"</p>
                <p class="font-bold">- Yan, Working in Japan</p>
            </div>
            <div class="bg-white p-4 shadow-md rounded-lg">
                <p>"The features are amazing and really helpful for me."</p>
                <p class="font-bold">- Roy, Tailwind University</p>
            </div>
            <div class="bg-white p-4 shadow-md rounded-lg">
                <p>"Would love to use it regularly and really help me during test."</p>
                <p class="font-bold">- May, Lecturer at Korea University</p>
            </div>
        </div>
        <h3 class="text-5xl md:text-7xl font-bold text-red-500 mb-5 mt-10">1000+</h3>
        <h3 class="text-2xl md:text-2xl font-bold text-red-500 mb-5 mt-4">New Users Everyday! Come Join Us Now !!</h3>

    </main>

    <footer class="bg-gray-50 text-black py-6 mt-20">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <div class="mb-4">
                <h3 class="font-bold text-lg">Contact Us</h3>
                <p>Email: creatorofulist@gmail.com</p>
                <p>Phone: +1 234 567 890</p>
            </div>
            <a href="profil.php" class="bg-red-300 text-black px-3 py-2 md:px-4 rounded hover:text-red-900 ">Our Profile</a>
            <div class="text-sm text-gray-400 mt-5">
                &copy; 2024 Ulist Website. Made with Love.
            </div>
        </div>
    </footer>

</body>

</html>