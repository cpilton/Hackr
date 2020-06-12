<?php

require 'database.php';

$user_id = $_COOKIE['user_id'];
$software_id = $_GET['id'];

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


$task = 'Uninstall Software';
$completion = (time() + ( ($level*20)) / ($cpu_level + $ram_level));


$start = time();
$item_id = $software_id;
$item_after = 'inactive';
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

	
session_start();
header("Location:tasks.php");

die();

?>