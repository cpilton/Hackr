<?php 

require 'database.php';

$hdd = 1;
$cpu = 1;
$ram = 1;

$hardware = $DBH->prepare('INSERT INTO hardware VALUES(null, :user_id, :hdd, :cpu, :ram)');
$hardware->bindParam('user_id', $_COOKIE['user_id']);
$hardware->bindParam('hdd', $hdd);
$hardware->bindParam('cpu', $cpu);
$hardware->bindParam('ram', $ram);
$hardware->execute();

$get_banks = $DBH->query( 'SELECT * FROM finance' );
$get_bank = $DBH->prepare('SELECT amount FROM finance WHERE id=:bank_id AND user_id=:uid');
$get_bank->bindParam('bank_id',$_POST['banks']);
$get_bank->bindParam('uid',$_COOKIE['user_id']);
$get_bank->execute();

$all_hardware = $DBH->query( 'SELECT * FROM hardware' );
$hardware = $DBH->prepare( 'SELECT id, user_id, hdd, cpu, ram FROM hardware');
		$hardware->execute();
		$hardware = $hardware->fetch();
		
		$user_id = $_COOKIE[ 'user_id' ];
		$count = 0;
		while ( $hardware = $all_hardware->fetch() ) {
		if ($_COOKIE['user_id'] == $hardware['user_id']) {
			$count++;
		}
		}

while ($get_bank = $get_banks->fetch()) {
	if($get_bank['id'] == $_POST['banks'] && $get_bank['user_id'] == $_COOKIE['user_id']) {
		$amount = $get_bank['amount'];

	}
}
$amount = $amount - (($count) * 1000000);

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

$log = $log . '\n' . $date . ' - localhost purchased a new server' ;

$update_log = $DBH->prepare("UPDATE logs SET log = '$log' WHERE user_id = '$log_id'");
	$update_log->execute() or die("Update Log Failed");

session_start();
header("Location:" . $_SESSION['current_page']);
die();

?>