<?php
if ( !isset( $_COOKIE[ 'user_id' ] ) ) {
	header( "Location: welcome.php" );
}
session_start();
$_SESSION[ 'current_page' ] = $_SERVER[ 'REQUEST_URI' ];

require 'scripts/database.php';
$tasks = $DBH->query( 'SELECT * FROM tasks' );
$taskss = $DBH->query( 'SELECT * FROM tasks' );
$get_sizes = $DBH->query( 'SELECT * from software' );
$all_hardware = $DBH->query( 'SELECT * FROM hardware' );

$time = time();
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Hackr</title>

	<link href="styles/styles.css" rel="stylesheet" type="text/css">
	<link href="styles/fonts.css" rel="stylesheet" type="text/css">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">
	<script src="js/jquery-1.7.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.animated-progressbar.js"></script>

</head>

<body>

	
<banner>
	<header>
		<img src="img/logo.svg" height="60px" id="banner_image"/>
	
	<nav>
		<table>
			<tr>
				<td>
					<a href="index.php" >Dashboard</a>
				</td>

				<td>
					<a href="hardware.php">Hardware</a>
				</td>

				<td>
					<a href="software.php" >Software</a>
				</td>

				<td>
					<a href="internet.php?redirect=true">Internet</a>
				</td>

				<td>
					<a href="tasks.php" id="current_page">Tasks</a>
				</td>
			</tr>
		</table>
	</nav>
	<login>
		<?php require 'scripts/database.php'; ?>
		<?php if (!isset($_COOKIE['user_id'])) : ?>
		
			<a href="session.php?a=login">Login</a> | <a href="session.php?a=register">Register</a>
		
		<?php else:
		
        $user = $DBH->prepare('SELECT first_name FROM users WHERE ID=:id');
        $user->bindParam('id', $_COOKIE['user_id']);
        $user->execute();
        $user = $user->fetch();
        $firstname = $user['first_name'];
		
        ?>
		Welcome back
			<?php print $firstname; ?>! - <a href="logout.php">Log out</a>
		
		<?php endif; ?>
	</login>
	
</header>
	<block>
	</block>
</banner>
	<?php
	
	if (isset($_COOKIE['user_id'])) {

	$user = $DBH->prepare( 'SELECT username, ip_address, ip_password FROM users WHERE ID=:id' );
	$user->bindParam( 'id', $_COOKIE[ 'user_id' ] );
	$user->execute();
	$user = $user->fetch();
	$username = $user[ 'username' ];
	$ip = $user[ 'ip_address' ];
	$password = $user[ 'ip_password' ];
	
	
	?>
	
	


	<container>
		<table>
			<tr>
				<td width="33%">
					<p class="align_left">Username:
						<?php print $username;?>
					</p>
				</td>
				<td width="33%">
					<p class="align_center">IP Address:
						<?php print $ip;?> <a id="change_ip">(Change)</a>
					</p>
				</td>
				<td width="33%">
					<p class="align_right">Password:
						<?php print $password;?> <a id="change_pwd">(Change)</a>
					</p>
				</td>
			</tr>

		</table>
	</container>
	<?php
		$hardware = $DBH->prepare( 'SELECT user_id, hdd, cpu, ram FROM hardware');
		$hardware->execute();
		$hardware = $hardware->fetch();
		
		$get_size = $DBH->prepare( 'SELECT user_id, size, type, active FROM software');
		$get_size->execute();
		$get_size = $get_size->fetch();
		
		
		$total_hdd = 0;
		$total_size = 0;
	$files = 0;
		$active_virus = 0;
		
		while ($get_size = $get_sizes->fetch()) {
			if ($_COOKIE['user_id'] == $get_size['user_id']) {
				$total_size = $total_size + $get_size['size'];
			}
			
		}
		
		while ( $hardware = $all_hardware->fetch() ) {
		if ($_COOKIE['user_id'] == $hardware['user_id']) {
			if ($hardware['hdd'] == 1) {
				$total_hdd = $total_hdd + 10;
			}
			else if ($hardware['hdd'] == 2) {
			$total_hdd = $total_hdd + 25;
			}
			else if ($hardware['hdd'] == 3) {
			$total_hdd = $total_hdd + 50;
			}
			else if ($hardware['hdd'] == 4) {
				$total_hdd = $total_hdd + 100;
			}
			else if ($hardware['hdd'] == 5) {
				$total_hdd = $total_hdd + 250;
			}
			else if ($hardware['hdd'] == 6) {
				$total_hdd = $total_hdd + 500;
			}
			else if ($hardware['hdd'] == 7) {
				$total_hdd = $total_hdd + 1000;
			}
			else if ($hardware['hdd'] == 8) {
				$total_hdd = $total_hdd + 10000;
			}
			else if ($hardware['hdd'] == 9) {
				$total_hdd = $total_hdd + 25000;
			}
			else if ($hardware['hdd'] == 10) {
				$total_hdd = $total_hdd + 50000;
			}
		}
		}
		
		$task = $DBH->prepare( 'SELECT id, user_id FROM tasks');
		$task->execute();
		$task = $task->fetch();


		$user_id = $_COOKIE[ 'user_id' ];
		$task_count = 0;
		while ( $task = $taskss->fetch() ) {
		if ( $user_id == $task['user_id']) {
			$task_count++;
		} }
			?>
	
		
	<container>
		<table class="overview_boxes_sw">
		<tr>
		<td>
				<img src="img/hdd_icon.png" height="40px">
			</td>
			<th>
				<?php
		if ($total_hdd < 1000) {
			echo(number_format(($total_size),0) . ' MB');?> / <?php
			echo(number_format(($total_hdd),0) . ' MB');
		}
		else if ($total_hdd >= 1000000) {
			echo(number_format(($total_size/1000000),4) . ' TB');?> / <?php
			echo(number_format(($total_hdd/1000000),4) . ' TB');
		}
		else if ($total_hdd >= 1000 ) {
			echo(number_format(($total_size/1000),1) . ' GB');?> / <?php
			echo(number_format(($total_hdd/1000),1) . ' GB');
		}
		
		?>
		
			</th>
			</tr>
		</table>
		
		<table class="overview_boxes_sw">
		<tr>
		<td>
				<img src="img/task_icon.png" height="40px">
			</td>
			<th>
			Active Tasks:
				<?php
		print $task_count;
		?>
		
			</th>
			</tr>
		</table>
		
		<table class="overview_boxes_sw">
		<tr>
		<td>
				<img src="img/hdd_icon.png" height="40px">
			</td>
			<th>
			Free Space:
				<?php
		$free_space = $total_hdd - $total_size;
		if ($free_space < 1000) {
			echo(number_format(($free_space),0) . ' MB');
		}
		else if ($free_space >= 1000000) {
			echo(number_format(($free_space/1000000),4) . ' TB');
		}
		else if ($free_space >= 1000 ) {
			echo(number_format(($free_space/1000),1) . ' GB');
		}
		
		?>
		
			</th>
			</tr>
		</table>
	</container>

	<container style="padding-top:20px; padding-bottom:15px">

		<?php

		$stat = "processing";

		$task = $DBH->prepare( 'SELECT id, user_id, task_type, start, completion, item_after, status FROM tasks');
		$task->bindParam( 'id', $_COOKIE[ 'user_id' ]  );
		$task->execute();
		$task = $task->fetch();


		$user_id = $_COOKIE[ 'user_id' ];
		$count = 0;
		while ( $task = $tasks->fetch() ):
			$status = $task[ "status" ];
		if ( $stat == $status && $user_id == $task['user_id']) {
			$id = $task[ "id" ];
			$count++;
			?>
		<table class="task_boxes">
			<tr>
				<th colspan="2">
					<p style="display: inline-block">
						<?php echo " " . $task[ "task_type" ] . " "; ?> </p> <a href="scripts/delete_task.php?id=<?php echo($task[ "id" ]) ?>"><img src="img/delete_icon.png" height="20px" style="float:right; display: inline-block;"></a>
				</th>
			</tr>
			<tr>
				<th colspan="2">

					<?php 
			
				 if (intval($time) < intval("".$task[ "completion" ]."")) { 
					 
					 $percent = (intval($time) - intval("".$task[ "start" ]."")) / (intval("".$task[ "completion" ]."") - intval("".$task[ "start" ].""));
			
							$duration = (intval("".$task[ "completion" ]."") - intval($time)) * 1000;
							?>
					<div class="progress">
						<div class="bar" data-progressbar="on" data-progressbar-begin="<?php echo($percent)?>" data-progressbar-end="1" data-progressbar-delay="100" data-progressbar-duration="<?php echo($duration)?>">

						</div>
					</div>
					<?php } else { ?>
					<div class="progress">
						<div class="bar" data-progressbar="on" data-progressbar-begin="1" data-progressbar-end="1" data-progressbar-delay="0" data-progressbar-duration="0"></div>
					</div>
					<?php } ?>
				</th>
			</tr>
			<tr>
				<th>
					<p>Change To</p>
				</th>
				<td>
					<p>
						<?php echo " " . $task[ "item_after" ] . " "; ?> </p>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<?php if (intval($time) > intval("".$task[ "completion" ]."")) { ?>
					<a href="scripts/task_finish.php?task=<?php echo $id?>">
						<div style="height:100%; width:100%">
							<p>Finish</p>
						</div>
					</a>
					<?php } else {?>
					<p>Processing</p>
					<?php } ?>
				</td>
			</tr>
		</table>
		<?php } endwhile; 
		if ($count == "0") {
		?>
		<h2>There are no running processes</h2>
		<?php } ?>
	</container>
	
	<?php } else { ?>
	<container>
		<h2>Please log in or create an account</h2>
	</container>
	<?php } 
	$banks = $DBH->query( 'SELECT * FROM finance' );
$bank = $DBH->prepare( 'SELECT id, user_id, amount, bank_name FROM finance ');
	$bank->execute();
	$bank = $bank->fetchAll();
?>
	
	<div id="ip" class="modal">

						<!-- Modal content -->
						<div class="modal-content">
							<div class="modal-header">
								<span id="close_ip" class="close">&times;</span>
								<h2>
									Change IP Address
								</h2>
							</div>
							<div class="modal-body">
								<p>
									
										You are about to change your IP address.
										
								</p>
								<p>
									This will cost £10,000.
								</p>
								<p>Select a bank account to pay with from the following list:</p>
								<form action="scripts/change_ip.php" method="post">
									
								<select required name="banks" style="margin:auto; display: block">
								<?php while ($bank = $banks->fetch()) {
									if ($bank['user_id'] == $_COOKIE['user_id']) {
				?>
									<option <?php if($bank['amount'] < 10000){ echo("disabled");}?> value="<?php echo($bank['id'])?>"><?php echo($bank['bank_name']) ?> (£<?php echo(number_format($bank['amount'],2)) ?>)</option>
									<?php } } ?>
									
								</select>
								
								<p style="margin-top: 20px"> 
										Changing your IP Address will take 10 minutes. You will not be refunded if you cancel the change.
									</p>
								
							</div>
							<div class="modal-footer">
							<input type="submit" value="Submit" id="modal_submit">
							</form>
							</div>
						</div>

					</div>
					<?php 

$banks = $DBH->query( 'SELECT * FROM finance' );
$bank = $DBH->prepare( 'SELECT id, user_id, amount, bank_name FROM finance ');
	$bank->execute();
	$bank = $bank->fetchAll();
?>
					<div id="pwd" class="modal">

						<!-- Modal content -->
						<div class="modal-content">
							<div class="modal-header">
								<span id="close_pwd" class="close">&times;</span>
								<h2>
									Change Password
								</h2>
							</div>
							<div class="modal-body">
								<p>
									
										You are about to change your Password.
										
								</p>
								<p>
									This will cost £10,000.
								</p>
								<p>Select a bank account to pay with from the following list:</p>
								<form action="scripts/change_password.php" method="post">
									
								<select required name="banks" style="margin:auto; display: block">
								<?php while ($bank = $banks->fetch()) {
									if ($bank['user_id'] == $_COOKIE['user_id']) {
				?>
									<option <?php if($bank['amount'] < 10000){ echo("disabled");}?> value="<?php echo($bank['id'])?>"><?php echo($bank['bank_name']) ?> (£<?php echo(number_format($bank['amount'],2)) ?>)</option>
									<?php } } ?>
									
								</select>
								
								<p style="margin-top: 20px"> 
										Changing your Password will take 10 minutes. You will not be refunded if you cancel the change.
									</p>
								
							</div>
							<div class="modal-footer">
							<input type="submit" value="Submit" id="modal_submit">
							</form>
							</div>
						</div>

					</div>

				<script>
					// Get the modal
					
					var modal = document.getElementById( 'ip' );

					// Get the button that opens the modal
					var btn = document.getElementById( "change_ip" );

					// Get the <span> element that closes the modal
					var span = document.getElementById('close_ip' );

					// When the user clicks the button, open the modal 
					btn.onclick = function () {
						modal.style.display = "block";
					}

					// When the user clicks on <span> (x), close the modal
					span.onclick = function () {
						modal.style.display = "none";
					}

					// When the user clicks anywhere outside of the modal, close it
					window.onclick = function ( event ) {
						if ( event.target == modal ) {
							modal.style.display = "none";
						}
					}
				</script>
				<script>
					// Get the modal
					
					var modal2 = document.getElementById( 'pwd' );

					// Get the button that opens the modal
					var btn2 = document.getElementById( "change_pwd" );

					// Get the <span> element that closes the modal
					var span2 = document.getElementById('close_pwd' );

					// When the user clicks the button, open the modal 
					btn2.onclick = function () {
						modal2.style.display = "block";
					}

					// When the user clicks on <span> (x), close the modal
					span2.onclick = function () {
						modal2.style.display = "none";
					}

					// When the user clicks anywhere outside of the modal, close it
					window.onclick = function ( event ) {
						if ( event.target == modal2 ) {
							modal2.style.display = "none";
						}
					}
				</script>
				
				<?php
	$get_times = $DBH->query( 'SELECT * FROM tasks' );
	$get_time = $DBH->prepare( 'SELECT user_id, completion FROM tasks');
		$get_time->execute();
		$get_time = $get_time->fetch();
	
	$seconds = 999998;
	
	while($get_time = $get_times->fetch()) {
		if ($get_time['user_id'] == $_COOKIE['user_id']) {
			$remaining = $get_time['completion'] - time();
			if ($remaining < $seconds && $remaining > 0) {
				$seconds = $remaining;
			}
		}
	}
$seconds= $seconds + 1;
	if ($seconds != 999999) {
	?>
	
	<meta http-equiv="refresh" content="<?php echo($seconds);?>;url=tasks.php" />
	<?php } ?>
</body>

<script type="text/javascript">
		$(".bar[data-progressbar='on']").each(function() {
			$(this).animatedProgressbar();
		});
	</script>

</html>
<!-- Callum Pilton -->