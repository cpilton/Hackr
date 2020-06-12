<?php
if ( !isset( $_COOKIE[ 'user_id' ] ) ) {
	header( "Location: welcome.php" );
}

require 'database.php';
require 'update_known_list.php';

session_start();
$_SESSION['current_page'] = $_SERVER['REQUEST_URI'];

$get_hack = $DBH->query( 'SELECT * FROM hacked_list' );
$banks = $DBH->query( 'SELECT * FROM finance' );
$softwares = $DBH->query( 'SELECT * FROM software' );
$logs = $DBH->query( 'SELECT * FROM logs' );
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Hackr</title>

	<link href="styles/styles.css" rel="stylesheet" type="text/css">
	<link href="styles/fonts.css" rel="stylesheet" type="text/css">
	<link rel="icon" href="img/favicon.ico" type="image/x-icon">

</head>

<body>

	
<banner>
	<header>
		<img src="img/logo.svg" height="60px" id="banner_image"/>
	
	<nav>
		<table>
			<tr>
				<td>
					<a href="index.php" id="current_page">Dashboard</a>
				</td>

				<td>
					<a href="hardware.php">Hardware</a>
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
<holder>
<table>
<tr >
<td rowspan="2">
	<container id="split_container" style="margin-right:5px">
		
		<?php 
		$bank = $DBH->prepare( 'SELECT user_id, amount FROM finance ');
	$bank->execute();
	$bank = $bank->fetch();
		
		$finance = 0;
		$accounts = 0;
		
		while ($bank = $banks->fetch()) {
			if ($bank['user_id'] == $_COOKIE['user_id']) {
				$finance = $finance + $bank['amount'];
				$accounts++;
			}
		}
		
		$software = $DBH->prepare( 'SELECT user_id, type, level FROM finance ');
	$software->execute();
	$software = $software->fetch();
		
		$hash = 0;
		$crack = 0;
		$fire = 0;
		$files = 0;
		
		while ($software = $softwares->fetch()) {
			if ($software['user_id'] == $_COOKIE['user_id']) {
				if ($software['type'] == "cracker" && $software['level'] > $crack) {
					$crack = $software['level'];
				}
				if ($software['type'] == "hasher" && $software['level'] > $hash) {
					$hash = $software['level'];
				}
				if ($software['type'] == "firewall" && $software['level'] > $fire) {
					$fire = $software['level'];
				}
				$files++;
			}
		}
		
		?>
		
		<table style="margin-top:0px">
			<tr>
				<th rowspan="5">
					<img src="img/hacker_profile.svg" height="150px">
				</th>
				<th colspan="2">
					User Information
				</th>
			</tr>
			<tr>
				<th>
					Username
				</th>
				<td>
					<?php print $username;?>
				</td>
			</tr>
			<tr>
				<th>
					IP Address
				</th>
				<td>
					<?php print $ip;?>
				</td>
			</tr>
			<tr>
				<th>
					Password
				</th>
				<td>
					<?php print $password;?>
				</td>
			</tr>
			<tr>
				<th>
					Finance
				</th>
				<td>
					£<?php print number_format($finance,2);?>
				</td>
			</tr>
		</table>
		
		<table style="margin-top: 20px;">
		<tr>
			<th colspan="3">
				Best Software
			</th>
			</tr>
			<td>
				<img src="img/cracker_icon.png" height="16px">
			</td>
			<th width="50%">
				Cracker
			</th>
			<td width="50%">
				<?php print $crack;?>
			</td>
			</tr>
			<td>
				<img src="img/hasher_icon.png" height="16px">
			</td>
			<th>
				Hasher
			</th>
			<td>
				<?php print $hash;?>
			</td>
			</tr>
			<td>
				<img src="img/firewall_icon.png" height="16px">
			</td>
			<th>
				Firewall
			</th>
			<td>
				<?php print $fire;?>
			</td>
			</tr>
		</table>
		
		<table style="margin-top: 20px;">
			<tr>
				<th colspan="3">
					Details
				</th>
			</tr>
			<tr>
			<td>
				<img src="img/bank_icon.png" height="16px">
			</td>
				<th width="50%">
					Bank Accounts
				</th>
				<td width="50%">
					<?php print $accounts;?>
				</td>
			</tr>
			<tr>
			<td>
				<img src="img/files_icon.png" height="16px">
			</td>
				<th>
					Number of Files
				</th>
				<td>
					<?php print $files;?>
				</td>
			</tr>
		</table>
		
		
	</container>
	</td>
			<td>
				
		
		 <?php 
		$hack = $DBH->query( 'SELECT * FROM hacked_list' );
		$hacked = $DBH->prepare( 'SELECT user_id, ip_address, ip_password, active FROM hacked_list');
			$hacked->execute();
			$hacked = $hacked->fetch();
		
		
		$ip1 = "";
		$ip2 = "";
		$ip3 = "";
		$ip4 = "";
		$ip5 = "";
		
		while($hacked = $hack->fetch()) {
			if ($hacked['user_id'] == $_COOKIE['user_id']) {
				if($hacked['ip_address'] == '92.4.47.15' && $hacked['active'] == 'yes') {
					$ip1 = $hacked['ip_password'];
				}
				else if($hacked['ip_address'] == '28.176.226.148' && $hacked['active'] == 'yes') {
					$ip2 = $hacked['ip_password'];
				}
				else if($hacked['ip_address'] == '179.200.139.57' && $hacked['active'] == 'yes') {
					$ip3 = $hacked['ip_password'];
				}
				else if($hacked['ip_address'] == '152.85.71.52' && $hacked['active'] == 'yes') {
					$ip4 = $hacked['ip_password'];
				}
				else if($hacked['ip_address'] == '49.190.86.29' && $hacked['active'] == 'yes') {
					$ip5 = $hacked['ip_password'];
				}
				
			}
		}
		?>
			
	<container id="split_container" style="height:105px;">
		<table id="notable_list" >
			<tr>
				<th colspan="3">Notable IP's</th>
			</tr>
			<tr>
			<th>
				Name
			</th>
			<th>
				IP Address
			</th>
			<th>
				Password
			</th>
			</tr>
			<tr>
			<td>
				Download Server
			</td>
			<td>
				<a href="internet.php?link=1.2.3.4">1.2.3.4</a>
			</td>
			<td>
				dl1
			</td>
			</tr>
			<tr>
				<td>
				FBI Suspect 1
			</td>
			<td>
				<a href="internet.php?link=92.4.47.15">92.4.47.15</a>
			</td>
			<td>
				<?php print $ip1 ?>
			</td>
			</tr>
			<tr>
				<td>
				FBI Suspect 2
			</td>
			<td>
				<a href="internet.php?link=28.176.226.148">28.176.226.148</a>
			</td>
			<td>
				<?php print $ip2 ?>
			</td>
			</tr>
			<tr>
				<td>
				FBI Suspect 3
			</td>
			<td>
				<a href="internet.php?link=179.200.139.57">179.200.139.57</a>
			</td>
			<td>
				<?php print $ip3 ?>
			</td>
			</tr>
			<tr>
				<td>
				FBI Suspect 4
			</td>
			<td>
				<a href="internet.php?link=152.85.71.52">152.85.71.52</a>
			</td>
			<td>
				<?php print $ip4 ?>
			</td>
			</tr>
			<tr>
				<td>
				FBI Suspect 5
			</td>
			<td>
				<a href="internet.php?link=49.190.86.29">49.190.86.29</a>
			</td>
			<td>
				<?php print $ip5 ?>
			</td>
			</tr>
		</table>
		
	</container>
	
	</td>
		</tr>
		<tr>
			<td>
				<container id="split_container" style="height:273px;">
				
					
		<?php
			
		$log = $DBH->prepare( 'SELECT id, user_id log FROM logs');
	$log->execute();
	$log = $log->fetch();
		
		while ($log = $logs->fetch()) {
			if ($log['user_id'] == $_COOKIE['user_id']) {
				
	
			
			?>
			
				<h2>Log File</h2>
				<form action="edit_log.php?log=<?php echo($log['id'])?>" method="post">
					<textarea name="log" id="log"> <?php echo($log['log'])?></textarea>
				
				<input type="submit" value="Save" id="submit_edit" style="width:100px;">
				
				
					</form>
					
					<?php } } ?>
				
</container>
			</td>
		</tr>
	</table>
	
	</holder>
	<container>
	
			
		<h2>Hacked IPs</h2>
	
		<input type="text" id="hacked_search_input" onkeyup="hackedSearch()" placeholder="Search for a Username">

<table id="hacked_search_table">
  <tr class="header">
    <th style="width:30%;">Username</th>
    <th style="width:30%;">IP Address</th>
    <th style="width:20%;">Password</th>
    <th style="width:20%;">Active</th>
  </tr>
  <tr>
  
  <?php $get_hacked = $DBH->prepare( 'SELECT user_id, ip_username, ip_address, ip_password, active FROM hacked_list');
			$get_hacked->execute();
			$get_hacked = $get_hacked->fetch();
		
		while($get_hacked = $get_hack->fetch()) {
			if ($get_hacked['user_id'] == $_COOKIE['user_id']) {
				?>
				<tr>
				<td>
					<?php echo($get_hacked['ip_username'])?>
				</td>
				
				<td>
					<?php echo($get_hacked['ip_address'])?>
				</td>
				
				<td>
					<?php echo($get_hacked['ip_password'])?>
				</td>
				
				<td>
					<?php echo($get_hacked['active'])?>
				</td>
				</tr>
				<?php
			}
		}
		?>
		
		
	</tr>
		</table>
		<script>
function hackedSearch() {
  var input, filter, table, tr, td, i;
  input = document.getElementById("hacked_search_input");
  filter = input.value.toUpperCase();
  table = document.getElementById("hacked_search_table");
  tr = table.getElementsByTagName("tr");

  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    } 
  }
}
</script>
		
		
		
		
		
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
	
</body>

</html>
<!-- Callum Pilton -->