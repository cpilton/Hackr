<?php
require 'database.php';

$username = $_POST['login_username'];
$password = $_POST['login_password'];

$logins = $DBH->query( 'SELECT * FROM users' );

$login = $DBH->prepare( "SELECT ip_password, username FROM users");
		$login->execute();
while ( $login = $logins->fetch() ) {
	if ($login['username'] == $username) {
$real_password = $login['ip_password'];
	}
}

if ($password == $real_password) {
	setcookie('accept', $username, time()+(60*60));
	header("Location: /internet.php");
	
}
else {
	setcookie('accept', 'fail', time()+(60*60));
	header("Location: /internet.php?status=fail");
}

?>