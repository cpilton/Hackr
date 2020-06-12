<?php

require 'database.php';

$id = $_GET['id'];
$no = 'no';
$zero = 0;

$seek_software = $DBH->prepare(" UPDATE software SET hidden = '$no', hider_level = '$zero' WHERE id = '$id' ");
	$seek_software->execute() or die();

session_start();
header("Location:" . $_SESSION['current_page']);

die();

?>