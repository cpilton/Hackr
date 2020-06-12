<?php

require 'database.php';

$user_id = $_COOKIE['user_id'];

$task = "IP Reset";

$start = time();

$completion = time() + (60*10);

$status = "processing";

$item_id = 0;

$size = 0;

$new_ips = $DBH->query( 'SELECT * from users' );
    $new_ip = $DBH->prepare('SELECT ip_address FROM users');
$new_ip->execute();
$new_ip = $new_ip->fetch();
$count = 1;

while($count > 0) {
	$count = 0;
	$ip_address = "".mt_rand(0,255).".".mt_rand(0,255).".".mt_rand(0,255).".".mt_rand(0,255);
while($new_ip = $new_ips->fetch()) {
	if ($new_ip['ip_address'] == $ip_address) {
		$count++;
	}
}
}



$change_ip = $DBH->prepare('INSERT INTO tasks VALUES(null, :user_id, :task_type, :start, :completion, :item_id, :item_after, :size, :status)');

$change_ip->bindParam('user_id', $user_id);
$change_ip->bindParam('task_type', $task);
$change_ip->bindParam('start', $start);
$change_ip->bindParam('completion', $completion);
$change_ip->bindParam('item_id', $item_id);
$change_ip->bindParam('item_after', $ip_address);
$change_ip->bindParam('size', $size);
$change_ip->bindParam('status', $status);

$change_ip->execute();

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
$amount = $amount - (10000);

$bank_id = $_POST['banks'];
$user_id = $_COOKIE['user_id'];


$update_finance = $DBH->prepare(" UPDATE finance SET amount = '$amount' WHERE id = '$bank_id' AND user_id = '$user_id' ");
	$update_finance->execute() or die();


	
session_start();
header("Location:tasks.php");

die();
?>