<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['user_id'])) {
    echo "<script>
            alert('Session has expired or you are not logged in. Please login again.');
            window.location.href = 'login.php';
          </script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$sql_user = "SELECT username, filepath FROM users WHERE id = ?";
$stmt_user = $db->prepare($sql_user);
$stmt_user->execute([$user_id]);
$user = $stmt_user->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found!";
    exit();
}

$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "SELECT * FROM todo_lists WHERE user_id = ?";

if ($search_query) {
    $sql .= " AND title LIKE ?";
    $params = [$user_id, "%$search_query%"];
} else {
    $params = [$user_id];
}

$sql .= " ORDER BY category";
$stmt = $db->prepare($sql);
$stmt->execute($params);
$lists = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categories = [
    'Work' => [],
    'Personal' => [],
    'Other' => []
];

foreach ($lists as $list) {
    $categories[$list['category']][] = $list;
}
?>

<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
    .poppins-regular {
        font-family: "Poppins", sans-serif;
        font-weight: 400;
        font-style: normal;
    }

    .todo-card {
        background-color: #fafaf9;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .todo-card h3 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #991b1b;
    }

    .todo-card h4 {
        font-size: 15px;
        font-weight: 400;
        margin-bottom: 10px;
    }

    .todo-card li {
        margin-bottom: 8px;
        font-size: 15px;

    }

    .todo-list {
        list-style: disc;
        margin-left: 20px;
    }
</style>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>

<body class="p-6 bg-red-50 poppins-regular">
    <div class="mb-5 flex flex-col sm:flex-row items-center justify-between">
        <img class="w-32 h-22" src="../photos/logo.png"></img>
        <h1 class="text-2xl text-center font-bold mb-4 sm:mb-0">Welcome, <?= htmlspecialchars($user['username']) ?>! What do you want to do today?</h1>
        <div class="flex items-center mt-4 sm:mt-0">
            <a href="create_todo.php" class="bg-red-700 text-white px-5 py-2 mr-2 rounded-md hover:bg-red-500 transition">Create To-Do List</a>
            <a href="create_todo.php"><span class="material-icons-outlined mr-4 mt-2">add_task</span></a>
            <a href="profile.php">
                <img src="<?= htmlspecialchars($user['filepath']) ?>" class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-full cursor-pointer object-cover hover:opacity-50" alt="Profile Photo">
            </a>
        </div>
    </div>

    </div>
    <form method="GET" action="" class="flex gap-4 mb-6">
        <input type="text" name="search" value="<?= htmlspecialchars($search_query) ?>" placeholder="Search To-Do Lists"
            class="border border-red-800 p-2 rounded w-full">
        <button type="submit" class="bg-blue-600 hover:bg-blue-300 text-white px-4 py-2 rounded">Search</button>
        <button type="button" class="bg-red-500 hover:bg-red-300 text-white px-4 py-2 rounded"
            onclick="window.location.href='<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>';">Reset</button>
    </form>

    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-4">Work</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($categories['Work'] as $list): ?>
                <a href="view_todo.php?id=<?= urlencode($list['id']) ?>" class="todo-card hover:bg-gray-200">
                    <h3><?= htmlspecialchars($list['title']) ?></h3>
                    <ul class="todo-list">
                        <?php
                        $sql_tasks = "SELECT * FROM tasks WHERE todo_id = ?";
                        $stmt = $db->prepare($sql_tasks);
                        $stmt->execute([$list['id']]);
                        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if (!empty($tasks)):
                            echo '<h4>' . htmlspecialchars($tasks[0]['description']) . '</h4>';
                            foreach ($tasks as $task): ?>
                                <li><?= htmlspecialchars($task['task']) ?></li>
                        <?php endforeach;
                        endif; ?>
                    </ul>
                </a>
            <?php endforeach; ?>

            <?php if (empty($categories['Work'])): ?>
                <div class="text-gray-700">No to-do lists found.</div>
            <?php endif; ?>
        </div>

        <h2 class="text-xl font-semibold mb-4 mt-5">Personal</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($categories['Personal'] as $list): ?>
                <a href="view_todo.php?id=<?= urlencode($list['id']) ?>" class="todo-card hover:bg-gray-200">
                    <h3><?= htmlspecialchars($list['title']) ?></h3>
                    <ul class="todo-list">
                        <?php
                        $sql_tasks = "SELECT * FROM tasks WHERE todo_id = ?";
                        $stmt = $db->prepare($sql_tasks);
                        $stmt->execute([$list['id']]);
                        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if (!empty($tasks)):
                            echo '<h4>' . htmlspecialchars($tasks[0]['description']) . '</h4>';
                            foreach ($tasks as $task): ?>
                                <li><?= htmlspecialchars($task['task']) ?></li>
                        <?php endforeach;
                        endif; ?>
                    </ul>
                </a>
            <?php endforeach; ?>

            <?php if (empty($categories['Personal'])): ?>
                <div class="text-gray-700">No to-do lists found.</div>
            <?php endif; ?>
        </div>

        <h2 class="text-xl font-semibold mb-4 mt-5">Other</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($categories['Other'] as $list): ?>
                <a href="view_todo.php?id=<?= urlencode($list['id']) ?>" class="todo-card hover:bg-gray-200">
                    <h3><?= htmlspecialchars($list['title']) ?></h3>
                    <ul class="todo-list">
                        <?php
                        $sql_tasks = "SELECT * FROM tasks WHERE todo_id = ?";
                        $stmt = $db->prepare($sql_tasks);
                        $stmt->execute([$list['id']]);
                        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if (!empty($tasks)):
                            echo '<h4>' . htmlspecialchars($tasks[0]['description']) . '</h4>';
                            foreach ($tasks as $task): ?>
                                <li><?= htmlspecialchars($task['task']) ?></li>
                        <?php endforeach;
                        endif; ?>
                    </ul>
                </a>
            <?php endforeach; ?>

            <?php if (empty($categories['Other'])): ?>
                <div class="text-gray-700">No to-do lists found.</div>
            <?php endif; ?>
        </div>
    </div>
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