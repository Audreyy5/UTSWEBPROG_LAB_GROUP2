<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$todo_id = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$todo_id) {
    echo "Invalid to-do list ID.";
    exit();
}

$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

$task_sql = "SELECT * FROM tasks WHERE todo_id = ?";

if ($filter == 'checked') {
    $task_sql .= " AND is_checked = 1";
} elseif ($filter == 'unchecked') {
    $task_sql .= " AND is_checked = 0";
}

if ($search_query) {
    $task_sql .= " AND task LIKE ?";
}

$sql = "SELECT * FROM todo_lists WHERE user_id = ? ORDER BY category";
$stmt = $db->prepare($sql);
$stmt->execute([$user_id]);
$lists = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View To-Do List Tasks</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .poppins-regular {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body class="bg-amber-100 p-8 poppins-regular">
    <a href="dashboard.php" class="text-blue-700 hover:text-blue-500 font-bold text-lg hover:underline">&larr;
        Back</a>
    <div class="max-w-lg mx-auto bg-white shadow-lg rounded-lg p-8 mt-16">
        <?php
        $stmt_tasks = $db->prepare($task_sql);
        if ($search_query) {
            $stmt_tasks->execute([$todo_id, "%$search_query%"]);
        } else {
            $stmt_tasks->execute([$todo_id]);
        }
        $tasks = $stmt_tasks->fetchAll(PDO::FETCH_ASSOC);
        foreach ($tasks as $task):
            if ($todo_id == $task['todo_id']) {
                foreach ($lists as $list):
                    if ($todo_id == $list['id']) {
                        $list_title = $list['title'];
                        $list_category = $list['category'];
                        break;
                    }
                endforeach;
            } else {
                echo "Error";
            }
        endforeach;
        ?>
        <h1 class="text-2xl font-bold my-4"><?= htmlspecialchars($list_category) ?> -
            <?= htmlspecialchars($list_title) ?></h1>
        <p class="text-sm text-gray-700 mb-4">
            <?= htmlspecialchars($tasks[0]['description'] ?? 'No description available') ?></p>

        <form method="GET" action="" class="flex gap-4 mb-4">
            <input type="hidden" name="id" value="<?= htmlspecialchars($todo_id) ?>">
            <input type="text" name="search" value="<?= htmlspecialchars($search_query) ?>"
                placeholder="Enter task name" class="border border-gray-300 p-2 rounded w-full">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">Search</button>
            <button type="button" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded"
                onclick="window.location.href='<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?id=<?= htmlspecialchars($todo_id) ?>';">Reset</button>
        </form>

        <form method="GET" action="" class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-6">
            <input type="hidden" name="id" value="<?= htmlspecialchars($todo_id) ?>">
            <input type="hidden" name="search" value="<?= htmlspecialchars($search_query) ?>">
            <div class="flex flex-col sm:flex-row gap-2">
                <label class="inline-flex items-center">
                    <input type="radio" name="filter" value="all" <?= $filter == 'all' ? 'checked' : '' ?>
                        class="form-radio">
                    <span class="ml-2">All</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="filter" value="unchecked" <?= $filter == 'unchecked' ? 'checked' : '' ?>
                        class="form-radio">
                    <span class="ml-2">Unchecked</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="filter" value="checked" <?= $filter == 'checked' ? 'checked' : '' ?>
                        class="form-radio">
                    <span class="ml-2">Checked</span>
                </label>
            </div>
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white px-16 py-2 rounded">Filter</button>
        </form>

        <form method="POST" action="update_tasks.php">
            <input type="hidden" name="list_id" value="<?= htmlspecialchars($todo_id) ?>">
            <ul class="mb-4">
                <?php if (count($tasks) > 0): ?>
                    <?php foreach ($tasks as $task): ?>
                        <li class="flex justify-between items-center mb-2">
                            <span><?= htmlspecialchars($task['task']) ?></span>
                            <input type="hidden" name="tasks[<?= $task['id'] ?>]" value="0">
                            <input type="checkbox" name="tasks[<?= $task['id'] ?>]" value="1" <?= isset($task['is_checked']) && $task['is_checked'] ? 'checked' : '' ?> class="form-checkbox">
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="text-gray-700">No tasks found.</li>
                <?php endif; ?>
            </ul>
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Update Changes</button>
        </form>

        <div class="flex justify-between items-center mt-4">
            <a href="edit_todo.php?id=<?= $todo_id ?>" class="bg-yellow-400 hover:bg-yellow-200 text-black px-4 py-2 rounded">Edit</a>
            <a href="delete_todo.php?id=<?= $todo_id ?>" onclick="return confirm('Are you sure?')"
                class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded">Delete</a>
        </div>
    </div>
</body>

</html>