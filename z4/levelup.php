<?php
	//skrypt do przejscia do nadkatalogu
	session_start();
	$_SESSION['current_dir'] = dirname($_SESSION['current_dir']) . '/'; //cofniecie sie o 1 w strukturze
	header('Location: index1.php');
?>