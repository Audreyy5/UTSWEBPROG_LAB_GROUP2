<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$todo_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($todo_id == 0) {
    header('Location: dashboard.php');
    exit();
}

$sql = "SELECT * FROM todo_lists WHERE id = ? AND user_id = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$todo_id, $user_id]);
$todo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$todo) {
    header('Location: dashboard.php');
    exit();
}

$sql_tasks = "SELECT * FROM tasks WHERE todo_id = ?";
$stmt_tasks = $db->prepare($sql_tasks);
$stmt_tasks->execute([$todo_id]);
$tasks = $stmt_tasks->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
    $category = htmlspecialchars($_POST['category'], ENT_QUOTES, 'UTF-8');
    $description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
    $new_tasks = $_POST['tasks'];
    $task_statuses = $_POST['tasks_checked'] ?? [];

    $sql_update = "UPDATE todo_lists SET title = ?, category = ? WHERE id = ? AND user_id = ?";
    $stmt_update = $db->prepare($sql_update);
    $stmt_update->execute([$title, $category, $todo_id, $user_id]);

    $sql_delete_tasks = "DELETE FROM tasks WHERE todo_id = ?";
    $stmt_delete_tasks = $db->prepare($sql_delete_tasks);
    $stmt_delete_tasks->execute([$todo_id]);

    $sql_insert_tasks = "INSERT INTO tasks (todo_id, task, description, is_checked) VALUES (?, ?, ?, ?)";
    $stmt_insert_tasks = $db->prepare($sql_insert_tasks);

    foreach ($new_tasks as $index => $task) {
        $task_clean = htmlspecialchars($task, ENT_QUOTES, 'UTF-8');
        $is_checked = isset($task_statuses[$index]) ? 1 : 0;
        $stmt_insert_tasks->execute([$todo_id, $task_clean, $description, $is_checked]);
    }

    header('Location: view_todo.php?id=' . $todo_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit To-Do List</title>
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

<body class="p-6 bg-orange-50 poppins-regular">
    <a href="dashboard.php" class="text-blue-500 hover:text-blue-700 ml-6 font-bold text-lg hover:underline">&larr;
        Back</a>
    <div class="mb-5">
        <div class="mb-5">
            <h1 class="text-3xl font-bold mb-10 text-center mt-6">Edit To-Do List</h1>
            <form action="edit_todo.php?id=<?= $todo_id ?>" method="POST"
                class="bg-white shadow-md rounded px-5 pt-6 pb-8 mb-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="title">Title:</label>
                    <input type="text" name="title" id="title" value="<?= htmlspecialchars($todo['title']) ?>" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                </div>

                <div class="mb-4 relative">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="category">Category:</label>
                    <div class="relative">
                        <select name="category" id="category" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="Work" <?= $todo['category'] == 'Work' ? 'selected' : '' ?>>Work</option>
                            <option value="Personal" <?= $todo['category'] == 'Personal' ? 'selected' : '' ?>>Personal</option>
                            <option value="Other" <?= $todo['category'] == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                            <span class="material-icons text-gray-700">arrow_drop_down</span>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description:</label>
                    <textarea id="description" name="description" maxlength="300" oninput="checkDescription()"
                        placeholder="Enter your description (max 300 characters)" rows="4" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?= htmlspecialchars($tasks[0]['description']) ?></textarea>
                </div>

                <label class="block text-gray-700 text-sm font-bold mb-2">Tasks:</label>
                <div id="tasks-container" class="mb-4">
                    <?php foreach ($tasks as $index => $task): ?>
                        <div class="mb-2 flex items-center">
                            <input type="text" name="tasks[]" value="<?= htmlspecialchars($task['task']) ?>" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="tasks_checked[<?= $index ?>]" <?= $task['is_checked'] ? 'checked' : '' ?> class="form-checkbox">
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>

                <button type="button" onclick="addTask()"
                    class="bg-green-600 hover:bg-blue-800 text-white py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-4">Add
                    another task</button>
                <button type="button" onclick="deleteCheckedTasks()"
                    class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-4">Delete
                    Checked Task</button>

                <div class="flex items-center justify-between">
                    <a href="dashboard.php">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded focus:outline-none focus:shadow-outline">Save Changes</button>
                    </a>

                    <a href="dashboard.php"
                        class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">Cancel</a>
                </div>
            </form>
        </div>

        <script>
            function addTask() {
                const container = document.getElementById('tasks-container');
                const newTaskDiv = document.createElement('div');
                newTaskDiv.className = 'mb-2 flex items-center';

                const newTaskInput = document.createElement('input');
                newTaskInput.type = 'text';
                newTaskInput.name = 'tasks[]';
                newTaskInput.placeholder = 'Task';
                newTaskInput.required = true;
                newTaskInput.className = 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2';

                const newCheckboxLabel = document.createElement('label');
                newCheckboxLabel.className = 'inline-flex items-center';
                newCheckboxLabel.innerHTML = '<input type="checkbox" name="tasks_checked[]" class="form-checkbox">';

                newTaskDiv.appendChild(newTaskInput);
                newTaskDiv.appendChild(newCheckboxLabel);
                container.appendChild(newTaskDiv);
            }

            function deleteCheckedTasks() {
                const container = document.getElementById('tasks-container');
                const taskDivs = container.getElementsByClassName('mb-2');

                for (let i = taskDivs.length - 1; i >= 0; i--) {
                    const taskDiv = taskDivs[i];
                    const checkbox = taskDiv.querySelector('input[type="checkbox"]');

                    if (checkbox.checked) {
                        taskDiv.remove();
                    }
                }
            }

            function checkDescription() {
                const descriptionInput = document.getElementById('description');
                const maxLength = 300;

                if (descriptionInput.value.length >= maxLength) {
                    alert('Description cannot exceed 300 characters.');
                }
            }
        </script>
    </div>
</body>

</html>