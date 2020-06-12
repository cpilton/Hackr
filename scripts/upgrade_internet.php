<?php

require 'database.php';


$level = $_GET['id'];
$new_level = $level + 1;

$user_id = $_COOKIE['user_id'];

$internet = $DBH->prepare("UPDATE users SET internet = '$new_level' WHERE id = '$user_id'");
	$internet->execute() or die("Update Internet Failed");


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
$amount = $amount - (($level*5) * 100);

$bank_id = $_POST['banks'];
$user_id = $_COOKIE['user_id'];


$update_finance = $DBH->prepare(" UPDATE finance SET amount = '$amount' WHERE id = '$bank_id' AND user_id = '$user_id' ");
	$update_finance->execute() or die();


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

$log = $log . '\n' . $date . ' - localhost upgraded the internet connection' ;

$update_log = $DBH->prepare("UPDATE logs SET log = '$log' WHERE user_id = '$log_id'");
	$update_log->execute() or die("Update Log Failed");

session_start();
header("Location:" . $_SESSION['current_page']);
die();

?>