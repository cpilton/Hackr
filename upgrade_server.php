<?php 

require 'database.php';

$hardware_type= $_POST['type'];
$server_id = $_GET['id'];





$all_hardware = $DBH->query( 'SELECT * FROM hardware' );
$hardware = $DBH->prepare( 'SELECT id, hdd, cpu, ram FROM hardware');
		$hardware->execute();
		$hardware = $hardware->fetch();
		
		
		while ( $hardware = $all_hardware->fetch() ) {
		if ($server_id== $hardware['id']) {
			$new_hdd = $hardware['hdd'] + 1;
			$new_cpu = $hardware['cpu'] + 1;
			$new_ram = $hardware['ram'] + 1;
			$hdd = $hardware['hdd'];
			$cpu = $hardware['cpu'];
			$ram = $hardware['ram'];
		}
		}

if ($hardware_type == 'cpu') {
	$hardware = $DBH->prepare("UPDATE hardware SET  cpu = '$new_cpu' WHERE id = '$server_id'");
$hardware->execute();
}

if ($hardware_type == 'hdd') {
	$hardware = $DBH->prepare("UPDATE hardware SET  hdd = '$new_hdd' WHERE id = '$server_id'");
$hardware->execute();
}

if ($hardware_type == 'ram') {
	$hardware = $DBH->prepare("UPDATE hardware SET  ram = '$new_ram' WHERE id = '$server_id'");
$hardware->execute();
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
$amount = $amount - (($ram + $cpu + $hdd) *15000);

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

$log = $log . '\n' . $date . ' - localhost upgraded the ' . $hardware_type . ' in server ' . $server_id ;

$update_log = $DBH->prepare("UPDATE logs SET log = '$log' WHERE user_id = '$log_id'");
	$update_log->execute() or die("Update Log Failed");

session_start();
header("Location:" . $_SESSION['current_page']);
die();

?>