<?php
	session_start();
	$_SESSION['current_dir'] = $_GET['current_dir']; //pobranie nazwy aktualnego katalogu
	
	header('Location: index1.php');
?>