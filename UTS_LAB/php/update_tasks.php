<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tasks'])) {
    $list_id = isset($_POST['list_id']) ? intval($_POST['list_id']) : null;
    $tasks = $_POST['tasks'];

    if ($list_id) {
        foreach ($tasks as $task_id => $is_checked) {
            $sql = "UPDATE tasks SET is_checked = ? WHERE id = ? AND todo_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$is_checked, $task_id, $list_id]);
        }
    }
    
    header("Location: view_todo.php?id=$list_id");
    exit();
} else {
    echo "No tasks received for updating.";
}
?>
