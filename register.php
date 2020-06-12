<?php

require 'scripts/database.php';

$ip_address = "".mt_rand(0,255).".".mt_rand(0,255).".".mt_rand(0,255).".".mt_rand(0,255);
$ip_password = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyz',ceil(6/strlen($x)))),1,6);
$password = sha1($_POST['password']);
$internet = 1;

$new_ips = $DBH->query( 'SELECT * from users' );
    $new_ip = $DBH->prepare('SELECT ip_address FROM users');
$new_ip->execute();
$new_ip = $new_ip->fetch();
$count = 1;

while($count > 0) {
	$count = 0;
	$ip_address = "".mt_rand(0,255).".".mt_rand(0,255).".".mt_rand(0,255).".".mt_rand(0,255);
while($new_ip = $new_ips->fetch()) {
	if ($new_ip['ip_address'] == $ip_address) {
		$count++;
	}
}
}

$collection = time();

$register = $DBH->prepare('INSERT INTO users VALUES(null, :first_name, :last_name, :email, :username, :password, :ip_address, :ip_password, :internet, :collection)');

$register->bindParam('first_name', $_POST['first_name']);
$register->bindParam('last_name', $_POST['last_name']);
$register->bindParam('email', $_POST['email']);
$register->bindParam('username', $_POST['username']);
$register->bindParam('password', $password);
$register->bindParam('ip_address', $ip_address);
$register->bindParam('ip_password', $ip_password);
$register->bindParam('internet', $internet);
$register->bindParam('collection', $collection);

$register->execute();

$get_user_ids = $DBH->query( 'SELECT * from users' );

$get_user_id = $DBH->prepare('SELECT id, username FROM users');
$get_user_id->execute();
$get_user_id = $get_user_id->fetch();

while ($get_user_id = $get_user_ids->fetch()) {
	if ($_POST['username'] == $get_user_id['username']) {
		$id = $get_user_id['id'];
	}
}


$hdd = 1;
$cpu = 1;
$ram = 1;

$hardware = $DBH->prepare('INSERT INTO hardware VALUES(null, :user_id, :hdd, :cpu, :ram)');
$hardware->bindParam('user_id', $id);
$hardware->bindParam('hdd', $hdd);
$hardware->bindParam('cpu', $cpu);
$hardware->bindParam('ram', $ram);
$hardware->execute();

$log = "";
	
$create_log = $DBH->prepare('INSERT INTO logs VALUES(null, :user_id, :log)');
$create_log->bindParam('user_id', $id);
$create_log->bindParam('log', $log);
$create_log->execute();

$bank_name = 'Basic Bank';
$amount = 0;

$create_bank = $DBH->prepare('INSERT INTO finance VALUES(null, :user_id, :bank_name, :amount)');
$create_bank->bindParam('user_id', $id);
$create_bank->bindParam('bank_name', $bank_name);
$create_bank->bindParam('amount', $amount);
$create_bank->execute();


header("Location: session.php?a=login&m=reg_succ");
die();

// Callum Pilton