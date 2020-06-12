<?php

$users = $DBH->query( 'SELECT * from users' );
$lists = $DBH->query( 'SELECT * FROM hacked_list' );

$user = $DBH->prepare( 'SELECT username, ip_address, ip_password FROM users' );
	$user->execute();

$list = $DBH->prepare( 'SELECT ip_username, ip_address, ip_password, active FROM hacked_list' );
	$list->execute();

$yes = 'yes';
$no = 'no';

while($list = $lists->fetch()) {
	$count = 0;
	$users = $DBH->query( 'SELECT * from users' );
	while($user = $users->fetch()) {
	
			
		
			
			$ip_username = $list['ip_username'];
			
			if ($user['username'] == $list['ip_username'] && $user['ip_password'] == $list['ip_password'] && $user['ip_address'] == $list['ip_address']) {
				$count++;
				
			}
		}
	
	
	
	if ($count == 0) {
					$update_list = $DBH->prepare(" UPDATE hacked_list SET active = '$no' WHERE ip_username = '$ip_username' ");
					$update_list->execute() or die();
				}
					else if ($count == 1) {
						
					$update_list = $DBH->prepare(" UPDATE hacked_list SET active = '$yes' WHERE ip_username = '$ip_username' ");
					$update_list->execute() or die();
	}
		
	}
?>