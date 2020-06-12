<?php

require 'scripts/database.php';


$password = sha1($_POST['password']);


$login = $DBH->prepare('SELECT id, password FROM users WHERE username=:username LIMIT 0,1;');


$login->bindParam('username', $_POST['username']);

$login->execute();

$user = $login->fetch();



if ($user['password'] == $password) {
	
	
	$get_logs = $DBH->query( 'SELECT * from logs' );
    $get_log = $DBH->prepare('SELECT user_id, log FROM logs');
	$get_log->execute();
	$get_log = $get_log->fetch();
	$log = "";
	while ($get_log = $get_logs->fetch()) {
		if ($user['id'] == $get_log['user_id']) {
			$log = $get_log['log'];
		}
	}
	
	
	echo($id);
	
	$date = date('d M Y h:i:s A');
	
	$log = $log . '\n' . $date . ' - localhost logged in ' ;
	
	
	$update_log = $DBH->prepare("UPDATE logs SET log = '$log' WHERE user_id = '$id'");
	$update_log->execute() or die("Update Log Failed");

   
   setcookie('user_id', $user['id'], time()+(60*60*24));
	session_start();
  header("Location:" . $_SESSION['current_page']);
	
    die();
} else {
    
    header("Location: session.php?a=login&m=invalid");
    die();
}
?>
// Callum Pilton