<?php

require 'database.php';

$software_id = $_GET['software'];

$task = "Software Upgrade";

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
$get_banks = $DBH->query( 'SELECT * FROM finance' );
$get_bank = $DBH->prepare('SELECT amount FROM finance WHERE id=:bank_id AND user_id=:uid');
$get_bank->bindParam('bank_id',$_POST['banks']);
$get_bank->bindParam('uid',$_COOKIE['user_id']);
$get_bank->execute();

while ($get_bank = $get_banks->fetch()) {
	if($get_bank['id'] == $_POST['banks'] && $get_bank['user_id'] == $_COOKIE['user_id']) {
		$amount = $get_bank['amount'];

	}
}
$amount = $amount - ($level * 1000);



	$new_size = $size + 24;
$new_level = $level + 0.1;


$completion = (time() + (60 * ($level*20)) / ($cpu_level + $ram_level));

$upgrade_software = $DBH->prepare('INSERT INTO tasks VALUES(null, :user_id, :task_type, :start, :completion, :item_id, :item_after, :size, :status)');

$upgrade_software->bindParam('user_id', $_COOKIE['user_id']);
$upgrade_software->bindParam('task_type', $task);
$upgrade_software->bindParam('start', $start);
$upgrade_software->bindParam('completion', $completion);
$upgrade_software->bindParam('item_id', $software_id);
$upgrade_software->bindParam('item_after', $new_level);
$upgrade_software->bindParam('size', $new_size);
$upgrade_software->bindParam('status', $status);


$upgrade_software->execute() or die();

$bank_id = $_POST['banks'];
$user_id = $_COOKIE['user_id'];


$update_finance = $DBH->prepare(" UPDATE finance SET amount = '$amount' WHERE id = '$bank_id' AND user_id = '$user_id' ");
	$update_finance->execute() or die();

session_start();
header("Location:" . $_SESSION['current_page']);

die();
?>