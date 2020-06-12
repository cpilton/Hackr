<?php
if ( !isset( $_COOKIE[ 'user_id' ] ) ) {
	header( "Location: welcome.php" );
}
require 'scripts/database.php';

session_start();
$_SESSION[ 'current_page' ] = $_SERVER[ 'REQUEST_URI' ];

$all_hardware = $DBH->query( 'SELECT * FROM hardware' );
$get_softwares = $DBH->query( 'SELECT * from software' );
$get_sizes = $DBH->query( 'SELECT * from software' );

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
					<a href="software.php" id="current_page">Software</a>
				</td>

				<td>
					<a href="internet.php?redirect=true">Internet</a>
				</td>

				<td>
					<a href="tasks.php">Tasks</a>
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
	if ( isset( $_COOKIE[ 'user_id' ] ) ) {

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
				$files++;
				if ($get_size['type'] == 'spam' &&  $get_size['active'] == 'yes') {
					$active_virus++;
				}
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
		
			?>
	<container>
	<table class="overview_boxes_sw">
		<tr >
		<td>
				<img src="img/files_icon.png" height="40px">
			</td>
			<th> Number of files: 
				<?php
			print $files;
		?>
		
			</th>
			</tr>
		</table>
		
		<table class="overview_boxes_sw">
		<tr >
		<td>
				<img src="img/spam_icon.png" height="40px">
			</td>
			<th> Number of active viruses: 
				<?php
			print $active_virus;
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
		
			</th>
			</tr>
		</table>
		
		
	</container>

	<container>

		<?php

		$get_software = $DBH->prepare( 'SELECT id, user_id, name, type, level, size, hidden, hider_level, active FROM software WHERE id:user_id' );
		$get_software->bindParam( 'view_id', $_COOKIE[ 'user_id' ] );
		$get_software->execute();
		
		$get_seekers = $DBH->query( 'SELECT * from software' );
				$get_seeker = $DBH->prepare( 'SELECT user_id, type, level,  active FROM software');
				$get_seeker->execute();
				$get_Seeker = $get_seeker->fetch();
				
				$seeker_level = 0;
				$hider_level = 0;
				
				while ($get_seeker = $get_seekers->fetch()) {
					if ($get_seeker['user_id'] == $_COOKIE['user_id'] && $get_seeker['type'] == 'seeker' && $get_seeker['level'] > $seeker_level && $get_seeker['active'] == 'yes') {
						$seeker_level = $get_seeker['level'];
					}
					if ($get_seeker['user_id'] == $_COOKIE['user_id'] && $get_seeker['type'] == 'hider' && $get_seeker['level'] > $hider_level && $get_seeker['active'] == 'yes') {
						$hider_level = $get_seeker['level'];
					}
				}
		
				
		?>


		<section id="web_content">

			<table id="software_list">
				<tr>
				<th>
					
				</th>
					<th>
						Name
					</th>
					<th>
						Type
					</th>
					<th>
						Level
					</th>
					<th>
						Size
					</th>
					<th colspan="4" width="20%">
					Actions
					</th>
				</tr>
				<?php
				while ( $get_software = $get_softwares->fetch() ) {
					if ($get_software['user_id'] == $_COOKIE['user_id'] && ($get_software['hidden'] == 'no' || $get_software['hider_level'] < $seeker_level)) {
					$banks = $DBH->query( 'SELECT * FROM finance' );
$bank = $DBH->prepare( 'SELECT id, user_id, amount, bank_name FROM finance ');
	$bank->execute();
	$bank = $bank->fetchAll();
						?>
				<tr>
					<td>
					<img src="img/<?php echo($get_software['type'])?>_icon.png" height="20px">
					</td>
					<td>
						<?php echo($get_software['name'])?>
					</td>
					<td>
						<?php echo($get_software['type'])?>
					</td>
					<td>
						<?php echo($get_software['level'])?>
					</td>
					<td>
					<?php 
						if ($get_software['size'] < 1000) {
						echo($get_software['size']); echo(" MB");
						}
						else if ($get_software['size'] < 1000000) {
							echo($get_software['size'] / 1000); echo(" GB");
						}
						else {
							echo($get_software['size'] / 1000000); echo(" TB");
						}
						?>
					</td>
					<td width="5%" class="tooltip">
						<?php if ($get_software['hidden'] == 'no') { ?>
						<a id="myBtn<?php echo($get_software['id'])?>"><img src="img/upgrade_icon.png" height="18px"><span class="tooltiptext">Upgrade</span></a>
						<?php } else { ?>
						<img src="img/upgrade_inactive_icon.png" height="18px">
						<?php } ?>
						
					</td>
					
				<td width="5%" class="tooltip">
						<?php if ($get_software['type'] == 'antivirus' || $get_software['type'] == 'collector') { 
							if  ($get_software['hidden'] == 'no') { ?>
							<span class="tooltiptext">Run</span>
						<a href="scripts/run_software.php?id=<?php echo($get_software['id'])?>"><img src="img/run_icon.png" height="16px"></a>
						<?php } else { ?>
							<img src="img/run_inactive_icon.png" height="16px">
						<?php  } } else if ($get_software['type'] == 'spam'){
							if ($get_software['active'] == 'no') {
							
						if  ($get_software['hidden'] == 'no') { ?>
						<span class="tooltiptext">Install</span>
							<a href="scripts/install_software.php?id=<?php echo($get_software['id'])?>"><img src="img/install_icon.png" height="16px"></a>
							<?php } else { ?>
							<img src="img/install_inactive_icon.png" height="16px">
							<?php } }
							
						} else {
						 if ($get_software['active'] == 'no') {
							
						if  ($get_software['hidden'] == 'no') { ?>
						<span class="tooltiptext">Install</span>
							<a href="scripts/install_software.php?id=<?php echo($get_software['id'])?>"><img src="img/install_icon.png" height="16px"></a>
							<?php } else { ?>
							<img src="img/install_inactive_icon.png" height="16px">
							<?php } } else {
							if  ($get_software['hidden'] == 'no') { ?>
							<span class="tooltiptext">Uninstall</span>
							<a href="scripts/uninstall_software.php?id=<?php echo($get_software['id'])?>"><img src="img/uninstall_icon.png" height="16px"></a>
							<?php } else { ?>
							<img src="img/uninstall_inactive_icon.png" height="16px">
							<?php } } } ?>
						</td>
						<td width="5%" class="tooltip"> 
						<?php if ($get_software['hidden'] == 'no') { 
							if ($hider_level > 0) { ?>
							<span class="tooltiptext">Hide</span>
							<a href="scripts/hide_software.php?id=<?php echo($get_software['id'])?>"><img src="img/hider_icon.png" height="16px"></a>
							<?php } else { ?>
							<img src="img/hider_inactive_icon.png" height="16px">
							<?php } } else {?>
							<span class="tooltiptext">Seek</span>
							<a href="scripts/seek_software.php?id=<?php echo($get_software['id'])?>"><img src="img/seeker_icon.png" height="16px"></a>
							<?php } ?>
						</td>
						
					<td width="5%" class="tooltip">
						<?php if ($get_software['type'] == 'spam'){
							 if ($get_software['active'] == 'no') {
							if ($get_software['hidden'] == 'no') { ?>
							<span class="tooltiptext">Delete</span>
							<a href="scripts/delete_web_software.php?id=<?php echo($get_software['id'])?>"><img src="img/delete_icon.png" height="18px"></a>
							<?php } else { ?>
							<img src="img/delete_inactive_icon.png" height="18px">
							<?php } 
							 } }  else if ($get_software['hidden'] == 'no') { ?>
							<span class="tooltiptext">Delete</span>
							<a href="scripts/delete_web_software.php?id=<?php echo($get_software['id'])?>"><img src="img/delete_icon.png" height="18px"></a>
							<?php } else { ?>
							<img src="img/delete_inactive_icon.png" height="18px">
							<?php } ?>
					</td>
				</tr>


<div id="myModal<?php echo($get_software['id'])?>" class="modal">

						<!-- Modal content -->
						<div class="modal-content">
							<div class="modal-header">
								<span id="close<?php echo($get_software['id'])?>" class="close">&times;</span>
								<h2>
									Upgrade <?php echo($get_software['name'])?>
								</h2>
							</div>
							<div class="modal-body">
								<p>
									
										You are upgrading the <?php echo($get_software['type'])?>, <?php echo($get_software['name'])?> from level <?php echo($get_software['level'])?> to level <?php echo($get_software['level'] + 0.1)?>.
										
								</p>
								<p>
									This upgrade will cost £<?php print number_format($get_software['level']*1000,2);?>.
								</p>
								<p>Select a bank account to pay with from the following list:</p>
								<form action="scripts/upgrade_software.php?software=<?php echo($get_software['id'])?>" method="post">
									
								<select required name="banks" style="margin:auto; display: block">
								<?php while ($bank = $banks->fetch()) {
									if ($bank['user_id'] == $_COOKIE['user_id']) {
				?>
									<option <?php if($bank['amount'] < $get_software['level']*1000){ echo("disabled");}?> value="<?php echo($bank['id'])?>"><?php echo($bank['bank_name']) ?> (£<?php echo(number_format($bank['amount'],2)) ?>)</option>
									<?php } } ?>
									
								</select>
								
								<p style="margin-top: 20px"> 
										Upgrading software may take time. You will not be refunded if you cancel this upgrade.
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
					
					var modal<?php echo($get_software['id'])?> = document.getElementById( 'myModal<?php echo($get_software['id'])?>' );

					// Get the button that opens the modal
					var btn<?php echo($get_software['id'])?> = document.getElementById( "myBtn<?php echo($get_software['id'])?>" );

					// Get the <span> element that closes the modal
					var span<?php echo($get_software['id'])?> = document.getElementById('close<?php echo($get_software['id'])?>' );

					// When the user clicks the button, open the modal 
					btn<?php echo($get_software['id'])?>.onclick = function () {
						modal<?php echo($get_software['id'])?>.style.display = "block";
					}

					// When the user clicks on <span> (x), close the modal
					span<?php echo($get_software['id'])?>.onclick = function () {
						modal<?php echo($get_software['id'])?>.style.display = "none";
					}

					// When the user clicks anywhere outside of the modal, close it
					window.onclick = function ( event ) {
						if ( event.target == modal<?php echo($get_software['id'])?> ) {
							modal<?php echo($get_software['id'])?>.style.display = "none";
						}
					}
				</script>
				<?php }
				}?>

			</table>
		</section>
		</section>

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
					
					var modal2 = document.getElementById( 'ip' );

					// Get the button that opens the modal
					var btn2 = document.getElementById( "change_ip" );

					// Get the <span> element that closes the modal
					var span2 = document.getElementById('close_ip' );

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
				<script>
					// Get the modal
					
					var modal3 = document.getElementById( 'pwd' );

					// Get the button that opens the modal
					var btn3 = document.getElementById( "change_pwd" );

					// Get the <span> element that closes the modal
					var span3 = document.getElementById('close_pwd' );

					// When the user clicks the button, open the modal 
					btn3.onclick = function () {
						modal3.style.display = "block";
					}

					// When the user clicks on <span> (x), close the modal
					span3.onclick = function () {
						modal3.style.display = "none";
					}

					// When the user clicks anywhere outside of the modal, close it
					window.onclick = function ( event ) {
						if ( event.target == modal3 ) {
							modal3.style.display = "none";
						}
					}
				</script>





</body>

</html>
<!-- Callum Pilton -->