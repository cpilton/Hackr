<?php

require 'database.php';

$user_id = $_COOKIE['user_id'];

$task = "Password Reset";

$start = time();

$completion = time() + (60*10);

$status = "processing";

$item_id = 0;

$size = 0;

$ip_password = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyz',ceil(6/strlen($x)))),1,6);

$change_password = $DBH->prepare('INSERT INTO tasks VALUES(null, :user_id, :task_type, :start, :completion, :item_id, :item_after, :size, :status)');

$change_password->bindParam('user_id', $user_id);
$change_password->bindParam('task_type', $task);
$change_password->bindParam('start', $start);
$change_password->bindParam('completion', $completion);
$change_password->bindParam('item_id', $item_id);
$change_password->bindParam('item_after', $ip_password);
$change_password->bindParam('size', $size);
$change_password->bindParam('status', $status);

$change_password->execute();
	
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
header("Location:/tasks.php");

die();

?>

<!-- Callum Pilton -->