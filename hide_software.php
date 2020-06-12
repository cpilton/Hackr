<?php

require 'database.php';


$get_hiders = $DBH->query( 'SELECT * from software' );
				$get_hider = $DBH->prepare( 'SELECT user_id, type, level,  active FROM software');
				$get_hider->execute();
				$get_hider = $get_hider->fetch();
				
				$hider_level = 0;
				
				while ($get_hider = $get_hiders->fetch()) {
					
					if ($get_hider['user_id'] == $_COOKIE['user_id'] && $get_hider['type'] == 'hider' && $get_hider['level'] > $hider_level && $get_hider['active'] == 'yes') {
						$hider_level = $get_hider['level'];
					}
				}


$id = $_GET['id'];
$yes = 'yes';

$hide_software = $DBH->prepare(" UPDATE software SET hidden = '$yes', hider_level = '$hider_level' WHERE id = '$id' ");
	$hide_software->execute() or die();

session_start();
header("Location:" . $_SESSION['current_page']);

die();

?>