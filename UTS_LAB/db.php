<?php   
$dsn = "mysql:host=localhost;dbname=uts_webprog_lab";
$db = new PDO($dsn, "root", "");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
