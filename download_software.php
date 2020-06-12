<?php

require 'database.php';

$user_id = $_COOKIE['user_id'];
$software_id = $_GET['id'];
$view_ip = $_COOKIE['view_ip'];

$get_levels = $DBH->query( 'SELECT * from software' );
				$get_level = $DBH->prepare( 'SELECT id, level, FROM software');
				$get_level->execute();
				$get_level = $get_level->fetch();
				
				$level = 0;
				
				while ($get_level = $get_levels->fetch()) {
					if ($get_level['id'] == $software_id) {
						$level = $get_level['level'];
					}
				}
					
					
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




$task = 'Download Software';
$completion = (time() + (60 * ($level*20))  / ($internet * 100));


$start = time();
$item_id = $software_id;
$item_after = 'downloaded';
$size = 0;
$status = 'processing';
					

$change_ip = $DBH->prepare('INSERT INTO tasks VALUES(null, :user_id, :task_type, :start, :completion, :item_id, :item_after, :size, :status)');

$change_ip->bindParam('user_id', $user_id);
$change_ip->bindParam('task_type', $task);
$change_ip->bindParam('start', $start);
$change_ip->bindParam('completion', $completion);
$change_ip->bindParam('item_id', $item_id);
$change_ip->bindParam('item_after', $item_after);
$change_ip->bindParam('size', $size);
$change_ip->bindParam('status', $status);

$change_ip->execute();


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
			$log = $get_log['log'] . '\n' . $date . ' - localhost downloaded a file from ' . $view_ip ;
		}
		else if ($view_id == $get_log['user_id'] ) {
			$view_log = $get_log['log'] . '\n' . $date . ' - ' . $user_ip . ' downloaded software from localhost' ;
		}
	}
	
	$log_id = $_COOKIE['user_id'];
	



$update_log = $DBH->prepare("UPDATE logs SET log = '$log' WHERE user_id = '$log_id'");
	$update_log->execute() or die("Update Log Failed");
	
	$update_log = $DBH->prepare("UPDATE logs SET log = '$view_log' WHERE user_id = '$view_id'");
	$update_log->execute() or die("Update Log Failed");
	
	
session_start();
header("Location:tasks.php");

die();

?>