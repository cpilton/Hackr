<?php
if ( !isset( $_COOKIE[ 'user_id' ] ) ) {
	header( "Location: welcome.php" );
}
require 'database.php';
require 'update_known_list.php';

session_start();
$_SESSION[ 'current_page' ] = $_SERVER[ 'REQUEST_URI' ];

$view_users = $DBH->query( 'SELECT * FROM users' );
$get_softwares = $DBH->query( 'SELECT * from software' );
$check_hacked = $DBH->query( 'SELECT * FROM hacked_list' );

if ( isset( $_POST[ 'address' ] ) ) {
	$view_ip = $_POST[ 'address' ];
	setcookie( 'view_ip', $_POST[ 'address' ], time() + ( 60 * 60 ) );
}
else if ( isset( $_GET[ 'return' ] ) ) {
	$view_ip = '1.1.1.1';
	setcookie( 'view_ip', '1.1.1.1', time() + ( 60 * 60 ) );
}
else if ( isset( $_GET[ 'status' ] ) ) {
	$view_ip = $_COOKIE[ 'view_ip' ];
	
}
else if (isset ($_GET['link'])) {
	$view_ip = $_GET['link'];
	setcookie( 'view_ip', $_GET['link'], time() + ( 60 * 60 ) );
}
else if ( isset ( $_GET['crack'])) {
	$view_ip = $_GET['crack'];
}
else if ( isset( $_GET['no_crack'])) {
	$view_ip = $_GET['no_crack'];
}
else if ( isset( $_GET['cracked'])) {
	$view_ip = $_GET['cracked'];
}
else if ( isset( $_COOKIE[ 'accept' ] ) ) {
	$view_ip = $_COOKIE[ 'view_ip' ];
}
else if ( isset( $_GET[ 'redirect' ] ) ) {
	if ( isset( $_COOKIE[ 'view_ip' ] ) ) {
		$view_ip = $_COOKIE[ 'view_ip' ];
	} else {
		$view_ip = '1.1.1.1';
	}
}   else {
	$view_ip = '1.1.1.1';
	setcookie( 'view_ip', '1.1.1.1', time() + ( 60 * 60 ) );
}


?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Hackr <?php print $accept;?></title>

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
					<a href="hardware.php">Hardware</a>
				</td>

				<td>
					<a href="software.php" >Software</a>
				</td>

				<td>
					<a href="internet.php?redirect=true"id="current_page">Internet</a>
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
	
	
		
	$view_user = $DBH->prepare( 'SELECT id, username, ip_password, ip_address FROM users WHERE ip_address=:ip' );
	$view_user->bindParam( 'ip', $view_ip );
	$view_user->execute();
	$rows = $view_user->fetchAll();
	$row_count = count( $rows );

	while ( $view_user = $view_users->fetch() ) {
		if ($view_user['ip_address'] == $view_ip) {
		$view_username = $view_user[ 'username' ];
		$view_id = $view_user['id'];
		}
	}

	if ( $row_count < 1 ) {

		if ( $view_ip == '1.1.1.1' ) {

			?>
	<container id="browser">
		<section id="tab">
			<section id="tab_bar">
				<img src="img/page_icon.png" height="15px" id="page_icon">
				<text>IP Search</text>
			</section>
			<span id="red_circle"></span>
			<span id="yellow_circle"></span>
			<span id="green_circle"></span>
		</section>
		<section id="search">
			<section id="search_bar">
				<img src="img/browser_icon.png" height="17px" id="browser_icon">
				<text>http://<?php echo($view_ip)?></text>
				<img src="img/submit_arrow.png" height="17px" id="submit_icon">
			</section>
		</section>

		<h2 id="webpage_title">IP Search</h2>
		<section id="search_box">
			<form action="internet.php" method="post">

				<input type="text" name="address" id="address" required>

				<input type="submit" value="Search" id="submit">
		</section>
		</form>

	</container>
	<?php

	} else {
		?>
	<container id="browser">
		<section id="tab">
		<a href="internet.php?return=true"><img src="img/back_arrow.png" height="18px" id="back_arrow"></a> 
			<section id="tab_bar">
				<img src="img/page_icon.png" height="15px" id="page_icon">
				<text>Page was not found</text>
			</section>
			<span id="red_circle"></span>
			<span id="yellow_circle"></span>
			<span id="green_circle"></span>
		</section>
		<section id="search">
			<section id="search_bar">
				<img src="img/browser_icon.png" height="17px" id="browser_icon">
				<text>http://0.0.0.0</text>
				<img src="img/submit_arrow.png" height="17px" id="submit_icon">
			</section>
		</section>

		<h2 id="webpage_title">Page was not found</h2>
		<h2 id="webpage_title">The address: <?php echo($view_ip)?> <br> does not exist</h2>

		<form id="return_button">
			<a href="internet.php?return=true" id="submit">Return</a>
		</form>

		</form>

	</container>
	<?php

	}
	} else {
		if (isset($_GET['crack'])) {
			?>
				
				<container id="browser">
		<section id="tab">
		<a href="internet.php?return=true"><img src="img/back_arrow.png" height="18px" id="back_arrow"></a> 
			<section id="tab_bar">
				<img src="img/page_icon.png" height="15px" id="page_icon">
				<text>
					<?php echo($view_username)?>
				</text>
			</section>
			<span id="red_circle"></span>
			<span id="yellow_circle"></span>
			<span id="green_circle"></span>
		</section>
		<section id="search">
			<section id="search_bar">
				<img src="img/browser_icon.png" height="17px" id="browser_icon">
				<text>http://<?php echo($view_ip)?></text>
				<img src="img/submit_arrow.png" height="17px" id="submit_icon">
			</section>
			<section id="web_content">
			
			<?php
				
				
  
				
	$get_level = $DBH->prepare( 'SELECT level, type, active FROM software WHERE user_id=:view_id' );
	$get_level->bindParam( 'view_id', $view_id );
	$get_level->execute();
				
			$level = 0;
			
			while ( $get_level = $get_softwares->fetch() ) {
				if ($get_level['user_id'] == $view_id && $get_level['type'] == 'hasher' && $get_level['level'] > $level && $get_level['active'] == 'yes')  {
					$level = $get_level['level'];
				}
			}
			
			$duration = $level * 600000 ;
			$seconds = $duration / 150000;
			
			setcookie( 'crack_ip', $view_ip, time() + ( 60 * 60 ) );
				?>
			
			<section id="login_box" style="padding-top: 20px">
			
			<h2 style="margin-bottom: 20px">Attempting to crack server</h2>
			
			<div class="progress" style="margin: auto">
					
						<div class="bar" data-progressbar="on" data-progressbar-begin="0" data-progressbar-end="1" data-progressbar-delay="1" data-progressbar-duration="<?php echo($duration)?>"></div>
					</div>
					
					<meta http-equiv="refresh" content="<?php echo($seconds);?>;url=crack_ip.php?success=<?php echo($view_ip)?>" />
			</section>
			</section>
		</section>
		

	</container>
			
			<script type="text/javascript">
		$(".bar[data-progressbar='on']").each(function() {
			$(this).animatedProgressbar();
		});
	</script>
			
			
			
			
			<?php
		}
		
		
		
		
		
		else if ( isset( $_COOKIE[ 'accept' ] ) && $_COOKIE[ 'accept' ] == $view_username ) {
			
			if (isset($_GET['view'])) {
				
				
				
				?>
				
				
			<container id="browser">
		<section id="tab">
		<a href="internet.php?return=true"><img src="img/back_arrow.png" height="18px" id="back_arrow"></a> 
			<section id="tab_bar">
				<img src="img/page_icon.png" height="15px" id="page_icon">
				<text>
					<?php echo($view_username)?>
				</text>
			</section>
			<span id="red_circle"></span>
			<span id="yellow_circle"></span>
			<span id="green_circle"></span>
		</section>
		<section id="search">
			<section id="search_bar">
				<img src="img/browser_icon.png" height="17px" id="browser_icon">
				<text>http://<?php echo($view_ip)?></text>
				<img src="img/submit_arrow.png" height="17px" id="submit_icon">
			</section>
			<section id="web_content">
			<table id="web_menu">
			<tr>
				<td width="20%" style="text-align:left;">
				<?php echo($view_username)?>
				</td>
				<td width="10%">
					<a href="internet.php" >FILES</a>
				</td>
				<td width="10%">
						<a href="internet.php?view=log" id="current_page">LOG</a>
				</td>
				<td style="text-align:right;">
				<a href="web_logout.php">Logout</a>
				</td>
				</tr>
				</table>
					
		<?php
			$logs = $DBH->query( 'SELECT * FROM logs' );
		$log = $DBH->prepare( 'SELECT id, user_id log FROM logs');
	$log->execute();
	$log = $log->fetch();
		
		while ($log = $logs->fetch()) {
			if ($log['user_id'] == $view_id) {
				
	
			
			?>
			
				<h2>Log File</h2>
				<form action="edit_log.php?log=<?php echo($log['id'])?>" method="post">
					<textarea name="log" id="full_log"> <?php echo($log['log'])?></textarea>
				
				<input type="submit" value="Edit" id="submit_edit" style="width:100px;">
				
				
					</form>
					
					<?php } } ?>
				
		
			</section>
		</section>
		

	</container>
		<?php
			}
			
			else {
				
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
				
				
				
			$get_software = $DBH->prepare( 'SELECT id, user_id, name, type, level, active, hidden, hider_level FROM software WHERE user_id=:view_id');
			$get_software->bindParam('view_id', $view_id);
			$get_software->execute();
			$get_software = $get_software->fetch();
				
				
				$all_hardware = $DBH->query( 'SELECT * FROM hardware' );
$get_sizes = $DBH->query( 'SELECT * from software' );
				$users = $DBH->query( 'SELECT * from users' );
				
				$hardware = $DBH->prepare( 'SELECT user_id, hdd, cpu, ram FROM hardware');
		$hardware->execute();
		$hardware = $hardware->fetch();
		
		$get_size = $DBH->prepare( 'SELECT user_id, size, type, active FROM software');
		$get_size->execute();
		$get_size = $get_size->fetch();
		
		$user = $DBH->prepare( 'SELECT id, internet FROM users');
		$user->execute();
		$user = $user->fetch();
				
				
		
		$total_hdd = 0;
		$total_size = 0;
	$files = 0;
				
				$internet = 0;
		
		while ( $user = $users->fetch() ) {
		if ($view_id == $user['id']) {
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
		
		while ($get_size = $get_sizes->fetch()) {
			if ($view_id == $get_size['user_id']) {
				$total_size = $total_size + $get_size['size'];
				$files++;
				
			}
			
		}
		
		while ( $hardware = $all_hardware->fetch() ) {
		if ($view_id == $hardware['user_id']) {
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
			
	<container id="browser">
		<section id="tab">
		<a href="internet.php?return=true"><img src="img/back_arrow.png" height="18px" id="back_arrow"></a> 
			<section id="tab_bar">
				<img src="img/page_icon.png" height="15px" id="page_icon">
				<text>
					<?php echo($view_username)?>
				</text>
			</section>
			<span id="red_circle"></span>
			<span id="yellow_circle"></span>
			<span id="green_circle"></span>
		</section>
		<section id="search">
			<section id="search_bar">
				<img src="img/browser_icon.png" height="17px" id="browser_icon">
				<text>http://<?php echo($view_ip)?></text>
				<img src="img/submit_arrow.png" height="17px" id="submit_icon">
			</section>
			<section id="web_content">
			<table id="web_menu">
			<tr>
				<td width="20%" style="text-align:left";>
				<?php echo($view_username)?>
				</td>
				<td width="10%">
					<a href="internet.php" id="current_page">FILES</a>
				</td>
				<td width="10%">
						<a href="internet.php?view=log">LOG</a>
				</td>
				<td style="text-align:right;">
				<a href="web_logout.php">Logout</a>
				</td>
				</tr>
				</table>
			</h2>
			<table id="web_software_list">
			<thead>
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
					<th width="20%">
					Actions
					</th>
				</tr>
				</thead>
				<tbody>
				<?php
				while ( $get_software = $get_softwares->fetch() ) { 
				if ($get_software['user_id'] == $view_id && ($get_software['hidden'] == 'no' || $get_software['hider_level'] < $seeker_level)) { ?>
					<tr>
					<td>
					<img src="img/<?php echo($get_software['type'])?>_icon.png" height="18px">
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
						<span class="tooltiptext">Download</span>
							<a href="download_software.php?id=<?php echo($get_software['id'])?>"><img src="img/download_icon.png" height="16px"></a>
							<?php } else { ?>
							<img src="img/download_inactive_icon.png" height="16px">
							<?php } ?>
						</td>
						<td width="5%" class="tooltip">
						<?php if ($get_software['type'] == 'antivirus' || $get_software['type'] == 'collector') { 
							 }  else if ($get_software['type'] == 'spam'){
							if ($get_software['active'] == 'no') {
							
						if  ($get_software['hidden'] == 'no') { ?>
						<span class="tooltiptext">Install</span>
							<a href="install_software.php?id=<?php echo($get_software['id'])?>"><img src="img/install_icon.png" height="16px"></a>
							<?php } else { ?>
							<img src="img/install_inactive_icon.png" height="16px">
							<?php } }
							
						}  else {
						 if ($get_software['active'] == 'no') {
							
						if  ($get_software['hidden'] == 'no') { ?>
						<span class="tooltiptext">Install</span>
							<a href="install_software.php?id=<?php echo($get_software['id'])?>"><img src="img/install_icon.png" height="16px"></a>
							<?php } else { ?>
							<img src="img/install_inactive_icon.png" height="16px">
							<?php } } else {
							if  ($get_software['hidden'] == 'no') { ?>
							<span class="tooltiptext">Uninstall</span>
							<a href="uninstall_software.php?id=<?php echo($get_software['id'])?>"><img src="img/uninstall_icon.png" height="16px"></a>
							<?php } else { ?>
							<img src="img/uninstall_inactive_icon.png" height="16px">
							<?php } } } ?>
						</td>
						<td width="5%" class="tooltip">
						<?php if ($get_software['hidden'] == 'no') { 
							if ($hider_level > 0) { ?>
							<span class="tooltiptext">Hide</span>
							<a href="hide_software.php?id=<?php echo($get_software['id'])?>"><img src="img/hider_icon.png" height="16px"></a>
							<?php } else { ?>
							<img src="img/hider_inactive_icon.png" height="16px">
							<?php } } else {?>
							<span class="tooltiptext">Seek</span>
							<a href="seek_software.php?id=<?php echo($get_software['id'])?>"><img src="img/seeker_icon.png" height="16px"></a>
							<?php } ?>
						</td>
						<td width="5%" class="tooltip">
						<?php if ($get_software['type'] == 'spam'){
							 if ($get_software['active'] == 'no') {
							if ($get_software['hidden'] == 'no') { ?>
							<span class="tooltiptext">Delete</span>
							<a href="delete_web_software.php?id=<?php echo($get_software['id'])?>"><img src="img/delete_icon.png" height="18px"></a>
							<?php } else { ?>
							<img src="img/delete_inactive_icon.png" height="18px">
							<?php } 
							 } }  else if ($get_software['hidden'] == 'no') { ?>
							<span class="tooltiptext">Delete</span>
							<a href="delete_web_software.php?id=<?php echo($get_software['id'])?>"><img src="img/delete_icon.png" height="18px"></a>
							<?php } else { ?>
							<img src="img/delete_inactive_icon.png" height="18px">
							<?php } ?>
					</td>
				</tr>
				<?php } 
				} ?>
				</tbody>
			
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
		$remaining = $total_hdd - $total_size;
		?>
		
			</th>
			</tr>
		</table>
		
		<table class="overview_boxes_sw">
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
			</tr>
		</table>
		
			</table>
			<table class="overview_boxes_sw">
			<td>
				<img src="img/upgrade_icon.png" height="40px">
			</td>
			<th>
				<a id="upload_file">Upload File</a>
			</th>
		</tr>
		</table>
			</section>
		</section>
		

	</container>

<?php																			
			}
	} else {
			
			$check_known = $DBH->prepare('SELECT ip_address, ip_password, active FROM hacked_list');
			$check_known->execute();
			$check_known = $check_known->fetch();
			
			$ip_pwd="";
			
			while ($check_known = $check_hacked->fetch()) {
				if ($check_known['ip_address'] == $view_ip && $check_known['active'] == "yes") {
					$ip_pwd = $check_known['ip_password'];
				}
			}

			?>
	<container id="browser">
		<section id="tab">
		<a href="internet.php?return=true"><img src="img/back_arrow.png" height="18px" id="back_arrow"></a> 
			<section id="tab_bar">
				<img src="img/page_icon.png" height="15px" id="page_icon">
				<text>
					<?php echo($view_username)?>
				</text>
			</section>
			<span id="red_circle"></span>
			<span id="yellow_circle"></span>
			<span id="green_circle"></span>
		</section>
		<section id="search">
			<section id="search_bar">
				<img src="img/browser_icon.png" height="17px" id="browser_icon">
				<text>http://<?php echo($view_ip)?></text>
				<img src="img/submit_arrow.png" height="17px" id="submit_icon">
			</section>
		</section>
		<h2 id="webpage_title">Login</h2>


		<section id="login_box">
			<form action="login_check.php" method="post">

				<p id="login_form_text">Username</p>

				<input type="text" name="login_username" id="login_form" value="<?php echo($view_username)?>" required>

				<p id="login_form_text">Password</p>

				<input type="text" name="login_password" id="login_form" required value="<?php echo($ip_pwd)?>">
				<input type="submit" value="Submit" id="submit">
				<a href="crack_ip.php?ip=<?php echo($view_ip)?>" id="submit">Crack</a>
				</form>
		</section>
<?php
if ( isset( $_GET[ 'status' ] ) ) { ?>
			<h2 style="margin-top:100px">Incorrect password entered</h2>
		<?php } ?>
		<?php
if ( isset( $_GET[ 'no_crack' ] ) ) { ?>
			<h2 style="margin-top:100px">Your cracker is not good enough</h2>
		<?php } ?>
	</container>

	<?php
	}
	}
	?>
	
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
		$get_user_softwares = $DBH->query( 'SELECT * from software' );
		
$get_user_software = $DBH->prepare( 'SELECT id, user_id, name, level, size FROM software');
			$get_user_software->execute();
			$get_user_software = $get_user_software->fetch();
		
		
		
		
?>
					<div id="upload" class="modal">

						<!-- Modal content -->
						<div class="modal-content">
							<div class="modal-header">
								<span id="close_upload" class="close">&times;</span>
								<h2>
									Upload Software
								</h2>
							</div>
							<div class="modal-body">
								<p>
									
										You are about to upload your software to this server.
										
								</p>
								<p>
									
								</p>
								
								<form action="upload_software.php" method="post">
									
								<select required name="software" style="margin:auto; display: block">
								<?php while($get_user_software = $get_user_softwares->fetch()) {
			if ($get_user_software['user_id'] == $_COOKIE['user_id'])
			{
				?>
									<option <?php if($get_user_software['size'] > $remaining){ echo("disabled");}?> value="<?php echo($get_user_software['id'])?>"><?php echo($get_user_software['name']) ?> (<?php echo(number_format($get_user_software['level'],1)) ?>) - <?php 
					if ($get_user_software['size'] < 1000) {
			echo(number_format(($get_user_software['size']),0) . ' MB');
		}
		else if ($get_user_software['size'] >= 1000000) {
			echo(number_format(($get_user_software['size']/1000000),4) . ' TB');
		}
		else if ($get_user_software['size'] >= 1000 ) {
			echo(number_format(($get_user_software['size']/1000),1) . ' GB');
			?></option>
									<?php } } } ?>
									
								</select>
								
								<p style="margin-top: 20px"> 
										Software Uploads may take time. You will be redirected to the tasks page. 
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
				
				<script>
					// Get the modal
					
					var modal3 = document.getElementById( 'upload' );

					// Get the button that opens the modal
					var btn3 = document.getElementById( "upload_file" );

					// Get the <span> element that closes the modal
					var span3 = document.getElementById('close_upload' );

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