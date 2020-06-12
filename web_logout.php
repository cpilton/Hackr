<?php
	setcookie('accept', 'fail', time()+(60*60));
	header("Location: internet.php");
?>