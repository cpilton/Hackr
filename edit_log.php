<?php
require 'database.php';
$log_id = $_GET['log'];
$new_log = $_POST['log'];

$log_update = $DBH->prepare(" UPDATE logs SET log = '$new_log' WHERE id = '$log_id'");
	$log_update->execute() or die();

session_start();
header("Location:" . $_SESSION['current_page']);
?>