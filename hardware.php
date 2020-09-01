<?php
if ( !isset( $_COOKIE[ 'user_id' ] ) ) {
	header( "Location: welcome.php" );
}
session_start();
$_SESSION[ 'current_page' ] = $_SERVER[ 'REQUEST_URI' ];

require 'database.php';
$all_hardware = $DBH->query( 'SELECT * FROM hardware' );
$users = $DBH->query( 'SELECT * FROM users' );

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
	<script src="js/jquery-3.5.1.min.js"></script>
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
					<a href="hardware.php" id="current_page">Hardware</a>
				</td>

				<td>
					<a href="software.php">Software</a>
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
		<?php require 'database.php'; ?>
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
		
		$user = $DBH->prepare( 'SELECT id, internet FROM users');
		$user->execute();
		$user = $user->fetch();
		
		$total_hdd = 0;
		$total_cpu = 0;
		$total_ram = 0;
		$internet = 0;
		
		while ( $user = $users->fetch() ) {
		if ($_COOKIE['user_id'] == $user['id']) {
			if ($user['internet'] == 1) {
				$internet = 50;
			}
			else if ($user['internet'] == 2) {
				$internet = 100;
			}
			else if ($user['internet'] == 3) {
				$internet = 200;
			}
			else if ($user['internet'] == 4) {
				$internet = 500;
			}
			else if ($user['internet'] == 5) {
				$internet = 1000;
			}
			else if ($user['internet'] == 6) {
				$internet = 10000;
			}
			else if ($user['internet'] == 7) {
				$internet = 40000;
			}
			else if ($user['internet'] == 8) {
				$internet = 70000;
			}
			else if ($user['internet'] == 9) {
				$internet = 200000;
			}
			else if ($user['internet'] == 10) {
				$internet = 500000;
			}
			else if ($user['internet'] == 11) {
				$internet = 1000000;
			}
			
		}
		}
		while ( $hardware = $all_hardware->fetch() ) {
		if ($_COOKIE['user_id'] == $hardware['user_id']) {
			$total_cpu = $total_cpu + $hardware['cpu'];
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
			
				if($hardware['ram'] == 1) {
						$total_ram = $total_ram + 512;
						}
		    else if($hardware['ram'] == 2) {
							$total_ram = $total_ram +1024;
						}
			else if($hardware['ram'] == 3) {
							$total_ram = $total_ram + 2048;
						}
			else if($hardware['ram'] == 4) {
							$total_ram = $total_ram + 4096;
						}
			else if($hardware['ram'] == 5) {
							$total_ram = $total_ram + 8192;
						}
			
		}
		}
			?>
	
	<container>
		
		<table class="overview_boxes">
		<tr >
		<td>
				<img src="img/hdd_icon.png" height="40px">
			</td>
			<th>
				<?php
		if ($total_hdd < 1000) {
			echo(number_format(($total_hdd),0) . ' MB');
		}
		else if ($total_hdd >= 1000000) {
			echo(number_format(($total_hdd/1000000),4) . ' TB');
		}
		else if ($total_hdd >= 1000 ) {
			echo(number_format(($total_hdd/1000),1) . ' GB');
		}
		
		?>
		
			</th>
			</tr>
		</table>
		
		<table class="overview_boxes">
			<td>
				<img src="img/cpu_icon.png" height="40px">
			</td>
			<th>
				<?php print number_format($total_cpu,0) ?> GHz
			</th>
		</tr>
		</table>
		
		<table class="overview_boxes">
		<tr>
		<td>
				<img src="img/ram_icon.png" height="40px">
			</td>
			<th>
				<?php
		if ($total_ram < 1000) {
			echo(number_format(($total_ram),0) . ' MB');
		}
		
		else if ($total_ram >= 1000 ) {
			echo(number_format(($total_ram/1000),3) . ' GB');
		}
		
		?>
		
			</th>
			</tr>
		</table>
		<table class="overview_boxes">
		<tr>
		<td>
				<img src="img/wifi_icon.png" height="40px">
			</td>
			<th>
				<?php
		if ($internet < 1000) {
			echo(number_format(($internet),0) . ' Kbps');
		}
		else if ($internet >= 1000000) {
			echo(number_format(($internet/1000000),0) . ' Gbps');
		}
		else if ($internet >= 1000 ) {
			echo(number_format(($internet/1000),0) . ' Mbps');
		}
		
		?>
		
			</th>
			<td>
				<a id="upgrade_internet"><img src="img/upgrade_icon.png" height="20px" style="margin-top:3px"></a>
			</td>
			</tr>
		</table>
	</container>

	<container style="padding-top:20px; padding-bottom:15px">

		<?php

	    $all_hardware = $DBH->query( 'SELECT * FROM hardware' );
		$hardware = $DBH->prepare( 'SELECT id, user_id, hdd, cpu, ram FROM hardware');
		$hardware->execute();
		$hardware = $hardware->fetch();
		

		$user_id = $_COOKIE[ 'user_id' ];
		$count = 0;
		while ( $hardware = $all_hardware->fetch() ):
		if ($_COOKIE['user_id'] == $hardware['user_id']) {
			$count++;
			?>
		<table class="task_boxes">
			<tr>
				<th colspan="3" width="250px">
					Server <?php echo($count)?>
					<a id="upgrade_server<?php echo($hardware['id'])?>"><img src="img/upgrade_icon.png" height="16px" style="float:right;">
				</th></a>
			</tr>
			<tr>
			<td>
				<img src="img/hdd_icon.png" height="20px" style="padding-top:5px;">
			</td>
				<th >
					HDD:
				</th>
				<td>
	  <?php if ($hardware['hdd'] == 1) {
				echo("10MB");
			}
			else if ($hardware['hdd'] == 2) {
				echo("25MB");
			}
			else if ($hardware['hdd'] == 3) {
				echo("50MB");
			}
			else if ($hardware['hdd'] == 4) {
				echo("100MB");
			}
			else if ($hardware['hdd'] == 5) {
				echo("250MB");
			}
			else if ($hardware['hdd'] == 6) {
				echo("500MB");
			}
			else if ($hardware['hdd'] == 7) {
				echo("1GB");
			}
			else if ($hardware['hdd'] == 8) {
				echo("10GB");
			}
			else if ($hardware['hdd'] == 9) {
				echo("25GB");
			}
			else if ($hardware['hdd'] == 10) {
				echo("50GB");
			}

					?>
				</td>
			</tr>
			<tr>
			<td>
				<img src="img/cpu_icon.png" height="20px" style="padding-top:5px;">
			</td>
			<th>
				CPU:
			</th>
				<td>
				<?php echo($hardware['cpu'])?> GHz
				</td>
			</tr>
			<tr>
			<td>
				<img src="img/ram_icon.png" height="20px" style="padding-top:5px;">
			</td>
				<th>
				RAM:
				</th>
				<td>
					<?php 
			if($hardware['ram'] == 1) {
							echo("512MB");
						}
		    else if($hardware['ram'] == 2) {
							echo("1GB");
						}
			else if($hardware['ram'] == 3) {
							echo("2GB");
						}
			else if($hardware['ram'] == 4) {
							echo("4GB");
						}
			else if($hardware['ram'] == 5) {
							echo("8GB");
						}
			
					?>	
				</td>
			</tr>
		</table>
		
		<?php 

$banks = $DBH->query( 'SELECT * FROM finance' );
$bank = $DBH->prepare( 'SELECT id, user_id, amount, bank_name FROM finance ');
	$bank->execute();
	$bank = $bank->fetchAll();
			
			$all_hardwares = $DBH->query( 'SELECT * FROM hardware' );
			$hardwares = $DBH->prepare( 'SELECT user_id, hdd, cpu, ram FROM hardware');
		$hardwares->execute();
		$hardwares = $hardwares->fetch();
			
?>
					<div id="upgrade<?php echo($hardware['id'])?>" class="modal">

						<!-- Modal content -->
						<div class="modal-content">
							<div class="modal-header">
								<span id="close_upgrade<?php echo($hardware['id'])?>" class="close">&times;</span>
								<h2>
									Upgrade Server <?php echo($count)?>
								</h2>
							</div>
							
							
							<div class="modal-body">
							
							<?php if ($hardware['hdd'] == 10 && $hardware['cpu'] == 5 && $hardware['ram'] == 5) { ?>
							<p>
					You have fully upgraded this server.</p>
								<?php
								} else {
				?>
				
			
							
								<p>
									<form action="upgrade_server.php?id=<?php echo($hardware['id'])?>" method="post">
										You are about to upgrade a server.
										</p><p>
										Select which hardware to upgrade from the following list:
								</p>
											
								<select required id="hardware_select" name="type" style="margin:auto; margin-top:20px; margin-bottom: 20px; display: block">
									
									<option  value="hdd" <?php if ($hardware['hdd'] == 10) { echo("disabled");}?>>Hard Drive (<?php if ($hardware['hdd'] == 1) {
				echo("10MB to 25MB");
			}
			else if ($hardware['hdd'] == 2) {
				echo("25MB to 50MB");
			}
			else if ($hardware['hdd'] == 3) {
				echo("50MB to 100MB");
			}
			else if ($hardware['hdd'] == 4) {
				echo("100MB to 250MB");
			}
			else if ($hardware['hdd'] == 5) {
				echo("250MB to 500MB");
			}
			else if ($hardware['hdd'] == 6) {
				echo("500MB to 1GB");
			}
			else if ($hardware['hdd'] == 7) {
				echo("1GB to 10GB");
			}
			else if ($hardware['hdd'] == 8) {
				echo("10GB to 25GB");
			}
			else if ($hardware['hdd'] == 9) {
				echo("25GB to 50GB");
			}
			else if ($hardware['hdd'] == 10) {
				echo("Fully Upgraded");
			}?>)</option>
									<option  value="cpu" <?php if ($hardware['cpu'] == 5) { echo("disabled");}?>>Processor  
									(<?php 
			for ($i = 0 ; $i < 5; $i++) {
				if ($hardware['cpu'] == $i) {
				echo($i . 'GHZ to '); echo($i+1 . 'GHZ') ;
			} 
				
			}
				if ($hardware['cpu'] == 5) {
					echo("Fully Upgraded");
				}
				
				?>)</option>
									<option  value="ram" <?php if ($hardware['ram'] == 5) { echo("disabled");}?>>Memory (<?php 
			if($hardware['ram'] == 1) {
							echo("512MB to 1GB");
						}
		    else if($hardware['ram'] == 2) {
							echo("1GB to 2GB");
						}
			else if($hardware['ram'] == 3) {
							echo("2GB to 4GB");
						}
			else if($hardware['ram'] == 4) {
							echo("4GB to 8GB");
						}
			else if($hardware['ram'] == 5) {
							echo("Fully Upgraded");
						}
			
					?>)	</option>
										</select>
								</p>
								<p>
									This upgrade will cost £<?php echo(number_format(($hardware['ram'] + $hardware['cpu'] + $hardware['hdd']) *15000))?>
									
									
							
								</p>
								<p>Select a bank account to pay with from the following list:</p>
								
									
								<select required name="banks" style="margin:auto; display: block; margin-top:20px; ">
								<?php while ($bank = $banks->fetch()) {
									if ($bank['user_id'] == $_COOKIE['user_id']) {
				?>
									<option <?php if($bank['amount'] < (($hardware['ram'] + $hardware['cpu'] + $hardware['hdd']) *15000)){ echo("disabled");}?> value="<?php echo($bank['id'])?>"><?php echo($bank['bank_name']) ?> (£<?php echo(number_format($bank['amount'],2)) ?>)</option>
									<?php } } ?>
									
								</select>
								
								<p style="margin-top: 20px"> 
										Your upgrade will be avaliable instantly upon purchase. This is not refundable.
									</p>
									
								 <?php } ?>
							</div>
							<div class="modal-footer">
							<?php if ($hardware['hdd'] == 10 && $hardware['cpu'] == 5 && $hardware['ram'] == 5) { 
			} else {?>
							<input type="submit" value="Submit" id="modal_submit">
							<?php } ?>
							</form>
							</div>
							
			
						</div>

					</div>
					
		
		
		<script>
					// Get the modal
					
					var moda<?php echo($hardware['id'])?> = document.getElementById( 'upgrade<?php echo($hardware['id'])?>' );

					// Get the button that opens the modal
					var bt<?php echo($hardware['id'])?> = document.getElementById( "upgrade_server<?php echo($hardware['id'])?>" );

					// Get the <span> element that closes the modal
					var spa<?php echo($hardware['id'])?> = document.getElementById('close_upgrade<?php echo($hardware['id'])?>' );

					// When the user clicks the button, open the modal 
					bt<?php echo($hardware['id'])?>.onclick = function () {
						moda<?php echo($hardware['id'])?>.style.display = "block";
					}

					// When the user clicks on <span> (x), close the modal
					spa<?php echo($hardware['id'])?>.onclick = function () {
						moda<?php echo($hardware['id'])?>.style.display = "none";
					}

					// When the user clicks anywhere outside of the modal, close it
					window.onclick = function ( event ) {
						if ( event.target == moda<?php echo($hardware['id'])?> ) {
							moda<?php echo($hardware['id'])?>.style.display = "none";
						}
					}
				</script>
		
		
		
		
		<?php
		
		} endwhile; 
		?>
		<a id="add_server"><img src="img/add_icon.png" height="40px" style="margin:40px;"></a>
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
								<form action="change_ip.php" method="post">
									
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
								<form action="change_password.php" method="post">
									
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
										<?php 

$banks = $DBH->query( 'SELECT * FROM finance' );
$bank = $DBH->prepare( 'SELECT id, user_id, amount, bank_name FROM finance ');
	$bank->execute();
	$bank = $bank->fetchAll();
?>
					<div id="add" class="modal">

						<!-- Modal content -->
						<div class="modal-content">
							<div class="modal-header">
								<span id="close_add" class="close">&times;</span>
								<h2>
									Buy new Server
								</h2>
							</div>
							<div class="modal-body">
								<p>
									
										You are about to buy a new Server.
										
								</p>
								<p>
									This will cost £<?php echo(number_format(($count + 1) * 1000000,2))?>
								</p>
								<p>Select a bank account to pay with from the following list:</p>
								<form action="add_server.php" method="post">
									
								<select required name="banks" style="margin:auto; display: block">
								<?php while ($bank = $banks->fetch()) {
									if ($bank['user_id'] == $_COOKIE['user_id']) {
				?>
									<option <?php if($bank['amount'] < ($count * 1000000)){ echo("disabled");}?> value="<?php echo($bank['id'])?>"><?php echo($bank['bank_name']) ?> (£<?php echo(number_format($bank['amount'],2)) ?>)</option>
									<?php } } ?>
									
								</select>
								
								<p style="margin-top: 20px"> 
										Your new server will be avaliable instantly upon purchase. This is not refundable.
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
					<div id="internet" class="modal">

						<!-- Modal content -->
						<div class="modal-content">
							<div class="modal-header">
								<span id="close_internet" class="close">&times;</span>
								<h2>
									Upgrade Internet
								</h2>
							</div>
							<div class="modal-body">
							<?php 
								if ($internet_level == 11) {
									?>
									<p>You have fully upgraded your internet connection.
									 </p> <?php
								}
								else {
									?>
								
							
	
								<p>
									
										You are about to upgrade your internet connection speed.
										
										
										
								</p>
								<p>
									This will cost £<?php echo(number_format(pow(5, $internet_level)*3,2))?>
								</p>
								<p>Select a bank account to pay with from the following list:</p>
								<form action="upgrade_internet.php?id=<?php echo($internet_level)?>" method="post">
								
								
									
								<select required name="banks" style="margin:auto; display: block">
								<?php while ($bank = $banks->fetch()) {
									if ($bank['user_id'] == $_COOKIE['user_id']) {
				?>
									<option <?php if($bank['amount'] < ($internet_level*250000)){ echo("disabled");}?> value="<?php echo($bank['id'])?>"><?php echo($bank['bank_name']) ?> (£<?php echo(number_format($bank['amount'],2)) ?>)</option>
									<?php } } ?>
									
								</select>
								
								<p style="margin-top: 20px"> 
										Your internet connection will be instantly upgraded. This is not refundable.
									</p>
									<?php } ?>
							</div>
							<div class="modal-footer">
							<?php 
								if ($internet_level == 11) { } 
								else {
									?>
							<input type="submit" value="Submit" id="modal_submit">
							<?php } ?>
							</form>
							</div>
						</div>

					</div>

			
				
				<script>
					// Get the modal
					
					var modal4 = document.getElementById( 'ip' );

					// Get the button that opens the modal
					var btn4 = document.getElementById( "change_ip" );

					// Get the <span> element that closes the modal
					var span4 = document.getElementById('close_ip' );

					// When the user clicks the button, open the modal 
					btn4.onclick = function () {
						modal4.style.display = "block";
					}

					// When the user clicks on <span> (x), close the modal
					span4.onclick = function () {
						modal4.style.display = "none";
					}

					// When the user clicks anywhere outside of the modal, close it
					window.onclick = function ( event ) {
						if ( event.target == modal4 ) {
							modal4.style.display = "none";
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
				<script>
					// Get the modal
					
					var modal3 = document.getElementById( 'add' );

					// Get the button that opens the modal
					var btn3 = document.getElementById( "add_server" );

					// Get the <span> element that closes the modal
					var span3 = document.getElementById('close_add' );

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
	<script>
					// Get the modal
					
					var modal4 = document.getElementById( 'internet' );

					// Get the button that opens the modal
					var btn4 = document.getElementById( "upgrade_internet" );

					// Get the <span> element that closes the modal
					var span4 = document.getElementById('close_internet' );

					// When the user clicks the button, open the modal 
					btn4.onclick = function () {
						modal4.style.display = "block";
					}

					// When the user clicks on <span> (x), close the modal
					span4.onclick = function () {
						modal4.style.display = "none";
					}

					// When the user clicks anywhere outside of the modal, close it
					window.onclick = function ( event ) {
						if ( event.target == modal4 ) {
							modal4.style.display = "none";
						}
					}
</script>
</body>




</html>
<!-- Callum Pilton -->