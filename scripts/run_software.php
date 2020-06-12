<?php

require 'database.php';

$user_id = $_COOKIE['user_id'];
$software_id = $_GET['id'];

$get_types = $DBH->query( 'SELECT * from software' );
				$get_type = $DBH->prepare( 'SELECT id, level, type FROM software');
				$get_type->execute();
				$get_type = $get_type->fetch();
				
				$level = 0;

				
				while ($get_type = $get_types->fetch()) {
					if ($get_type['id'] == $software_id) {
						$level = $get_type['level'];
						$type = $get_type['type'];
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

if ($type == 'collector') {
$task = 'Collect Money';
$item_after = 'collected';
}
else if ($type == 'antivirus') {
$task = 'Antivirus Scan';
$item_after = 'scanned';
}

$completion = (time() + (60 * ($level*20)) / ($cpu_level + $ram_level));

$start = time();
$item_id = $level;

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


$get_logs = $DBH->query( 'SELECT * from logs' );
    $get_log = $DBH->prepare('SELECT user_id, log FROM logs');
	$get_log->execute();
	$get_log = $get_log->fetch();
	while ($get_log = $get_logs->fetch()) {
		if ($_COOKIE['user_id'] == $get_log['user_id']) {
			$log = $get_log['log'];
		}
	}
	
	$log_id = $_COOKIE['user_id'];
	
	$date = date('d M Y h:i:s A');

$log = $log . '\n' . $date . ' - localhost ran software' ;

$update_log = $DBH->prepare("UPDATE logs SET log = '$log' WHERE user_id = '$log_id'");
	$update_log->execute() or die("Update Log Failed");

	
session_start();
header("Location:/tasks.php");

die();



?>