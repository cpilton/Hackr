<?php
require 'database.php';

$task_id = $_GET["id"];

$delete_query = "DELETE FROM tasks WHERE id='$task_id'";
$DBH->exec($delete_query);

session_start();
header("Location:/tasks.php");

?>