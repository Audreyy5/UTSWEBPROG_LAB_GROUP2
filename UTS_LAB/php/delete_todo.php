<?php
session_start();
require_once('db.php');

$list_id = $_GET['id'];
$sql = "DELETE FROM todo_lists WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$list_id]);

header('Location: dashboard.php');
exit();
?>
