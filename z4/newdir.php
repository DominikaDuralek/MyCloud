<?php
	session_start();
	$user =  $_SESSION['username'];
	
	$main_dir = $user . '/'; //katalog macierzysty zalogowanego uzytkownika
	
	error_reporting(0);
	$dir_name = $_POST['dir_name'] . '/';
	if (!file_exists($main_dir . $dir_name)) { //jesli katalog jeszcze nie istnieje
		mkdir($main_dir . $dir_name, 0777, true); //tworzenie nowego katalogu
	}
	header('Location: index1.php');
?>