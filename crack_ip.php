<?php
require 'database.php';

$crack_level = 0;
$hash_level = 0;
$view_ip = $_COOKIE[ 'view_ip' ];

$get_ids = $DBH->query( 'SELECT * from users' );
$get_levels = $DBH->query( 'SELECT * from software' );

$get_level = $DBH->prepare('SELECT FROM software user_id, type, level, active)');
$get_level->execute();
$get_level = $get_level->fetch();

$get_id = $DBH->prepare('SELECT FROM users user_id, username, ip_address, ip_password)');
$get_id->execute();
$get_id = $get_id->fetch();

while ($get_id = $get_ids->fetch()) {
	if ($view_ip == $get_id['ip_address']) {
		$view_id = $get_id['id'];
		$view_username = $get_id['username'];
		$view_password = $get_id['ip_password']; 
}
}


if (isset($_GET["ip"])) {
	while ($get_level = $get_levels->fetch()) {
	if ($get_level['user_id'] == $_COOKIE['user_id'] && $get_level['type'] == "cracker" && $get_level['level'] > $crack_level && $get_level['active'] == 'yes') {
		$crack_level = $get_level['level'];
	}
		
	if ($get_level['user_id'] == $view_id && $get_level['type'] == "hasher" && $get_level['level'] > $hash_level && $get_level['active'] == 'yes') {
	$hash_level = $get_level['level'];
	}
}
	$ip = $_GET["ip"];
	if ($crack_level >= $hash_level) {
	
	header("Location:internet.php?crack=$ip");
}
	else {
		header("Location:internet.php?no_crack=$ip");
	}
}
else if (isset($_GET["success"])) {
	
	$active = "yes";
	$ip = $_GET["success"];
	
	
	
	$lists = $DBH->query( 'SELECT * FROM hacked_list' );


$list = $DBH->prepare( 'SELECT ip_username, ip_address, ip_password, active FROM hacked_list' );
	$list->execute();
	$list = $list->fetch();

$yes = 'yes';
$no = 'no';

	$count = 0;
while($list = $lists->fetch() ) {
	
			if ($view_username == $list['ip_username'] && $view_password == $list['ip_password'] && $view_ip == $list['ip_address']) {
				
				$count++;
				
				}
}
	
	if($count == 0) {
	$new_hack = $DBH->prepare('INSERT INTO hacked_list VALUES(null, :user_id, :ip_username, :ip_address, :ip_password, :active)');

$new_hack->bindParam('user_id', $_COOKIE['user_id']);
$new_hack->bindParam('ip_username', $view_username);
$new_hack->bindParam('ip_address', $view_ip);
	$new_hack->bindParam('ip_password', $view_password);
	$new_hack->bindParam('active', $active);
	$new_hack->execute();
				
	}
	
	
	$get_ids = $DBH->query( 'SELECT * from users' );
	$get_id = $DBH->prepare('SELECT id, username, ip_address, ip_password FROM users');
$get_id->execute();
$get_id = $get_id->fetch();

while ($get_id = $get_ids->fetch()) {
	if ($view_ip == $get_id['ip_address']) {
		$view_id = $get_id['id'];
}
	if ($_COOKIE['user_id'] == $get_id['id']) {
		$user_ip = $get_id['ip_address'];
	}
}
	
	$date = date('d M Y h:i:s A');
	
	$get_logs = $DBH->query( 'SELECT * from logs' );
    $get_log = $DBH->prepare('SELECT user_id, log FROM logs');
	$get_log->execute();
	$get_log = $get_log->fetch();
	while ($get_log = $get_logs->fetch()) {
		if ($_COOKIE['user_id'] == $get_log['user_id']) {
			$log = $get_log['log'] . '\n' . $date . ' - localhost cracked into ' . $view_ip ;
		}
		else if ($view_id == $get_log['user_id'] ) {
			$view_log = $get_log['log'] . '\n' . $date . ' - ' . $user_ip . ' cracked into localhost' ;
		}
	}
	
	$log_id = $_COOKIE['user_id'];
	 


$update_log = $DBH->prepare("UPDATE logs SET log = '$log' WHERE user_id = '$log_id'");
	$update_log->execute() or die("Update Log1 Failed");
	
	$update_log = $DBH->prepare("UPDATE logs SET log = '$view_log' WHERE user_id = '$view_id'");
	$update_log->execute() or die("Update Log2 Failed");
	
	header("Location:internet.php?cracked=$ip");
}



