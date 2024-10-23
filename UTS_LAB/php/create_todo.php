<?php
session_start();
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['user_id'])) {
        die('User ID is not set in the session.');
    }

    $user_id = $_SESSION['user_id'];

    $sql_check_user = "SELECT COUNT(*) FROM users WHERE id = ?";
    $stmt_check_user = $db->prepare($sql_check_user);
    $stmt_check_user->execute([$user_id]);
    if (!$stmt_check_user->fetchColumn()) {
        die('User ID does not exist in the users table.');
    }

    $title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
    $category = htmlspecialchars($_POST['category'], ENT_QUOTES, 'UTF-8');
    $description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
    $tasks = $_POST['tasks'];

    $sql = "INSERT INTO todo_lists (title, category, user_id) VALUES (?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->execute([$title, $category, $user_id]);

    $todo_id = $db->lastInsertId();

    $is_checked = 0;
    $sql_task = "INSERT INTO tasks (todo_id, task, description, is_checked) VALUES (?, ?, ?, ?)";
    $stmt_task = $db->prepare($sql_task);

    foreach ($tasks as $task) {
        $task_clean = htmlspecialchars($task, ENT_QUOTES, 'UTF-8');
        $stmt_task->execute([$todo_id, $task_clean, $description, $is_checked]);
    }

    header('Location: dashboard.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create To-Do List</title>
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

<body class="p-6 bg-[#fff7ed] poppins-regular">
    <a href="dashboard.php" class="text-blue-500 hover:text-blue-700 ml-6 font-bold text-lg hover:underline">&larr;
        Back</a>
    <div class="mb-5">
        <h1 class="text-3xl font-bold mb-10 mt-10 text-center text-red-900">Create To-Do List</h1>
        <form action="create_todo.php" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 ">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="title">Title:</label>
                <input type="text" name="title" id="title" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>

            <div class="mb-4 relative">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="category">Category:</label>
                <div class="relative">
                    <select name="category" id="category" required
                        class="block appearance-none w-full bg-white border text-gray-700 py-2 px-4 pr-10 rounded focus:outline-none focus:ring focus:ring-blue-300">
                        <option value="Work">Work</option>
                        <option value="Personal">Personal</option>
                        <option value="Other">Other</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                        <span class="material-icons text-gray-700">arrow_drop_down</span>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description:</label>
                <textarea id="description" name="description" maxlength="300" oninput="checkDescription()" placeholder="Enter your description (max 300 characters)" rows="4" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            </div>

            <label class="block text-gray-700 text-sm font-bold mb-2">Tasks:</label>
            <div id="tasks-container" class="mb-4">
                <input type="text" name="tasks[]" placeholder="Add your task" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-900 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <button type="button" onclick="addTask()" class="bg-green-700 hover:bg-green-600 text-white py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-4">Add another task</button>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-red-900 hover:bg-red-600 text-white py-2 px-4 rounded focus:outline-none focus:shadow-outline">Create</button>
                <a href="dashboard.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        function addTask() {
            const container = document.getElementById('tasks-container');
            const inputCount = container.getElementsByTagName('input').length;
            const newTaskInput = document.createElement('input');
            newTaskInput.type = 'text';
            newTaskInput.name = 'tasks[]';
            newTaskInput.placeholder = 'Task ' + (inputCount + 1);
            newTaskInput.required = true;
            newTaskInput.className = 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline';
            container.appendChild(newTaskInput);
            container.appendChild(document.createElement('br'));
        }

        function checkDescription() {
            const descriptionInput = document.getElementById('description');
            const maxLength = 300;

            if (descriptionInput.value.length >= maxLength) {
                alert('Description cannot exceed 300 characters.');
            }
        }
    </script>
</body>

</html>