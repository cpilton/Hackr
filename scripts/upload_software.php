<?php

require 'database.php';

$software_id = $_POST['software'];

$view_ip = $_COOKIE['view_ip'];

$task = "Software Upload";

$start = time();

$cpu_level = 1;
$ram_level = 1;

$get_levels = $DBH->query( 'SELECT * from hardware' );

$get_level = $DBH->prepare("SELECT user_id, cpu, ram FROM hardware");
	$get_level->execute();
while ( $get_level = $get_levels->fetch() ) {
	if($_COOKIE['user_id'] == $get_level['user_id']) {
	$cpu_level = $cpu_level + $get_level['cpu'];
		$ram_level = $ram_level + $get_level['ram'];
}
}

	$size = 0;

$status = "processing";

$get_versions = $DBH->query( 'SELECT * from software' );

$get_version = $DBH->prepare("SELECT level, size FROM software");
	$get_version->execute();

while ( $get_version = $get_versions->fetch() ) {
	if($get_version['id'] == $software_id) {
		$size = $get_version['size'];
	$level = $get_version['level'];
}
}


$get_internets = $DBH->query( 'SELECT * from users' );

$get_internet = $DBH->prepare("SELECT id, ip_address, internet FROM users");
	$get_internet->execute();

while ( $get_internet = $get_internets->fetch() ) {
	if ($get_internet['id'] == $user_id) {
		$user_internet = $get_internet['internet'];
	}
	else if ($get_internet['ip_address'] == $view_ip) {
		$view_internet = $get_internet['internet'];
	}
}

if ($view_internet < $user_internet) {
	$internet = $view_internet;
}
else {
	$internet = $user_internet;
}


$completion = (time() + (60 * ($level*20))  / ($internet * 100));

$new_size = 0;

$upgrade_software = $DBH->prepare('INSERT INTO tasks VALUES(null, :user_id, :task_type, :start, :completion, :item_id, :item_after, :size, :status)');

$upgrade_software->bindParam('user_id', $_COOKIE['user_id']);
$upgrade_software->bindParam('task_type', $task);
$upgrade_software->bindParam('start', $start);
$upgrade_software->bindParam('completion', $completion);
$upgrade_software->bindParam('item_id', $software_id);
$upgrade_software->bindParam('item_after', $view_ip);
$upgrade_software->bindParam('size', $new_size);
$upgrade_software->bindParam('status', $status);

$upgrade_software->execute() or die();

	
	$get_ids = $DBH->query( 'SELECT * from users' );
	$get_id = $DBH->prepare('SELECT id, username, ip_address, ip_password FROM users');
$get_id->execute();
$get_id = $get_id->fetch();

while ($get_id = $get_ids->fetch()) {
	if ($view_ip == $get_id['ip_address']) {
		$view_id = $get_id['id'];
}
	if ($_COOKIE['user_id'] == $get_id['id']) {
		$user_ip = $get_id['ip_address'];
	}
}
	
	$date = date('d M Y h:i:s A');
	
	$get_logs = $DBH->query( 'SELECT * from logs' );
    $get_log = $DBH->prepare('SELECT user_id, log FROM logs');
	$get_log->execute();
	$get_log = $get_log->fetch();
	while ($get_log = $get_logs->fetch()) {
		if ($_COOKIE['user_id'] == $get_log['user_id']) {
			$log = $get_log['log'] . '\n' . $date . ' - localhost uploaded software to ' . $view_ip ;
		}
		else if ($view_id == $get_log['user_id'] ) {
			$view_log = $get_log['log'] . '\n' . $date . ' - ' . $user_ip . ' uploaded software to localhost' ;
		}
	}
	
	$log_id = $_COOKIE['user_id'];
	



$update_log = $DBH->prepare("UPDATE logs SET log = '$log' WHERE user_id = '$log_id'");
	$update_log->execute() or die("Update Log Failed");
	
	$update_log = $DBH->prepare("UPDATE logs SET log = '$view_log' WHERE user_id = '$view_id'");
	$update_log->execute() or die("Update Log Failed");

session_start();
header("Location:/tasks.php");

die();
?>