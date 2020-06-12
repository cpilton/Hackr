<?php
require 'database.php';

$user_id = $_COOKIE['user_id'];
$task_id = $_GET["task"];
$new_status = "complete";
$delete_query = "DELETE FROM tasks WHERE id='$task_id'";

$task = $DBH->prepare( "SELECT ID, task_type, item_after, item_id, size FROM tasks WHERE ID=:id");
		$task->bindParam( 'id', $task_id );
		$task->execute();
		$task = $task->fetch();
$task_type = $task[ "task_type" ];
$item_after = $task[ "item_after" ];
$item_id = $task["item_id"];
$size = $task["size"];

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
	
if ($task_type == 'IP Reset') {
	$ip_reset = $DBH->prepare("UPDATE users SET ip_address = '$item_after' WHERE id = '$user_id'");
	$ip_reset->execute() or die("Update IP Failed");
	
	$DBH->exec($delete_query);
	
	
	
	$log = $log . '\n' . $date . ' - localhost performed an IP Reset' ;
	
	

	
}

else if ($task_type == 'Password Reset') {
	$ip_reset = $DBH->prepare("UPDATE users SET ip_password = '$item_after' WHERE id = '$user_id'");
	$ip_reset->execute() or die("Update Password Failed");
	
	$DBH->exec($delete_query);
	
	
	$log = $log . '\n' . $date . ' - localhost performed a Password Reset' ;
	
}

else if ($task_type == 'Software Upgrade') {
	$upgrade_level = $DBH->prepare("UPDATE software SET level = '$item_after'  WHERE id = '$item_id' AND user_id = '$user_id'");
	$upgrade_level->execute() or die("Update Software Failed");
	
	$upgrade_size = $DBH->prepare("UPDATE software SET size = '$size'  WHERE id = '$item_id' AND user_id = '$user_id'");
	$upgrade_size->execute() or die("Update Software Failed");
	
	$DBH->exec($delete_query);
	
	
	$log = $log . '\n' . $date . ' - localhost upgraded a software item' ;
	
	
}

else if ($task_type == 'Install Software') {
	
	$user_id = $_COOKIE['user_id'];
	
	$install_software = $DBH->prepare("UPDATE software SET active = 'yes', uploader_id = '$user_id' WHERE id = '$item_id'");
	$install_software->execute() or die("Install Software Failed");
	
	$DBH->exec($delete_query);
}

else if ($task_type == 'Uninstall Software') {
	$install_software = $DBH->prepare("UPDATE software SET active = 'no'  WHERE id = '$item_id'");
	$install_software->execute() or die("Uninstall Software Failed");
	
	$DBH->exec($delete_query);
}

else if ($task_type == 'Download Software') {
	
	$get_softwares = $DBH->query( 'SELECT * from software' );
				$get_software = $DBH->prepare( 'SELECT * FROM software');
				$get_software->execute();
				$get_software = $get_software->fetch();
				
				while ($get_software = $get_softwares->fetch()) {
					if ($get_software['id'] == $item_id) {
						
						$name = $get_software['name'];
						$type = $get_software['type'];
						$level = $get_software['level'];
						$size = $get_software['size'];
					}
				}
	
	$no = 'no';
	$zero = 0;
	
	$insert_software = $DBH->prepare('INSERT INTO software VALUES(null, :user_id, :uploader_id, :name, :type, :level, :size, :active, :hidden, :hider_level)');
	
	$insert_software->bindParam('user_id', $_COOKIE['user_id']);
	$insert_software->bindParam('uploader_id', $_COOKIE['user_id']);
	$insert_software->bindParam('name', $name);
	$insert_software->bindParam('type', $type);
	$insert_software->bindParam('level', $level);
	$insert_software->bindParam('size', $size);
	$insert_software->bindParam('active', $no);
	$insert_software->bindParam('hidden', $no);
	$insert_software->bindParam('hider_level', $zero);
	
	$insert_software->execute() or die("Download Software Failed");
	
	$DBH->exec($delete_query);
}

else if ($task_type == 'Delete Software') {
	

$delete_software = "DELETE FROM software WHERE id='$item_id'";

$DBH->exec($delete_software);
	$DBH->exec($delete_query);
}

else if ($task_type == 'Antivirus Scan') {
	$spam = 'spam';
	$yes = 'yes';
	$item_id = intval($item_id);
	$delete_virus = "DELETE FROM software WHERE type='$spam' AND user_id ='$user_id' AND level<'$item_id' AND active='$yes'";
	$DBH->exec($delete_virus);
	$DBH->exec($delete_query);
}

else if ($task_type == 'Software Upload') {
	
		$get_softwares = $DBH->query( 'SELECT * from software' );
				$get_software = $DBH->prepare( 'SELECT * FROM software');
				$get_software->execute();
				$get_software = $get_software->fetch();
				
				while ($get_software = $get_softwares->fetch()) {
					if ($get_software['id'] == $item_id) {
						
						$name = $get_software['name'];
						$type = $get_software['type'];
						$level = $get_software['level'];
						$size = $get_software['size'];
					}
				}
	
	$no = 'no';
	$zero = 0;
	
	$get_ids = $DBH->query( 'SELECT * FROM users' );
	$get_id = $DBH->prepare( 'SELECT id, ip_address FROM users');
	$get_id->execute();
	$get_id = $get_id->fetch();
	
	while ($get_id = $get_ids->fetch()) {
					if ($get_id['ip_address'] == $item_after) {
						$view_id = $get_id['id'];
					}
	}
	
	
	
	$insert_software = $DBH->prepare('INSERT INTO software VALUES(null, :user_id, :uploader_id, :name, :type, :level, :size, :active, :hidden, :hider_level)');
	$insert_software->bindParam('user_id', $view_id);
	$insert_software->bindParam('uploader_id', $view_id);
	$insert_software->bindParam('name', $name);
	$insert_software->bindParam('type', $type);
	$insert_software->bindParam('level', $level);
	$insert_software->bindParam('size', $size);
	$insert_software->bindParam('active', $no);
	$insert_software->bindParam('hidden', $no);
	$insert_software->bindParam('hider_level', $zero);
	
	$insert_software->execute() or die("Upload Software Failed");
	
	$DBH->exec($delete_query);
	
}

else if ($task_type == 'Collect Money') {
	
	$users = $DBH->query( 'SELECT * FROM users' );
	$user = $DBH->prepare( 'SELECT id, collection FROM users' );
	$user->execute();
	$user = $user->fetch();
	
	while ($user = $users->fetch()) {
		if($user['id'] == $_COOKIE['user_id']) {
			$collection = $user['collection'];
		}
	}
	
	$softwares = $DBH->query( 'SELECT * FROM software' );
	$software = $DBH->prepare( 'SELECT uploader_id, type, active FROM software' );
	$software->execute();
	$software = $software->fetch();
	
	$number = 0;
	$yes = 'yes';
	$type = 'spam';
	
	while ($software = $softwares->fetch()) {
		if ($software['uploader_id'] == $_COOKIE['user_id'] && $software['type'] == $type && $software['active'] == $yes ) {
			$number++;
		}
	}
	
	$time = time();
	
	$amount = (($time - $collection) * $number) * ($item_id / 10);
	
	$finances = $DBH->query( 'SELECT * FROM finance' );
	$finance = $DBH->prepare( 'SELECT user_id, amount FROM finance' );
	$finance->execute();
	$finance = $finance->fetch();
	
	while ($finance = $finances->fetch()) {
		if($finance['user_id'] == $_COOKIE['user_id']) {
			$old_amount = $finance['amount'];
		}
	}
	$new_amount = $old_amount + $amount;
	
	
	$user_id = $_COOKIE['user_id'];
	
	$update_amount = $DBH->prepare("UPDATE finance SET amount = '$new_amount' WHERE user_id = '$user_id'");
	$update_amount->execute() or die("Update Finance Failed");
	
	$update_time = $DBH->prepare("UPDATE users SET collection = '$time' WHERE id = '$user_id'");
	$update_time->execute() or die("Update Collection Time Failed");
	
	$DBH->exec($delete_query);
	
}

$update_log = $DBH->prepare("UPDATE logs SET log = '$log' WHERE user_id = '$log_id'");
	$update_log->execute() or die("Update Log Failed");



session_start();
header("Location:tasks.php");

die();
?>
