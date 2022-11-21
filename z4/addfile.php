<?php
	session_start();
	$user = $_SESSION['username']; //zalogowany uzytkownik
	$datetime = date('Y-m-d H:i:s');
	
	if (file_exists($_FILES["uploaded_file"]["tmp_name"]))
	{
		$dbhost="mariadb106.server701675.nazwa.pl"; $dbuser="server701675_domdur1"; $dbpassword="6D6zB4WuURKzU@h"; $dbname="server701675_domdur1";
		$connection = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname); //polaczenie z BD
		if (!$connection)
		{
			echo " MySQL Connection error." . PHP_EOL;
			echo "Errno: " . mysqli_connect_errno() . PHP_EOL;
			echo "Error: " . mysqli_connect_error() . PHP_EOL;
			exit;
		}
		
		$datetime = date('Y-m-d H:i:s');
		$target_dir = $_SESSION['current_dir']; //katalog zalogowanego uzytkownika
		
		$file_name = $_FILES["uploaded_file"]["name"];
		$file_extension = pathinfo($_FILES["uploaded_file"]["name"], PATHINFO_EXTENSION); //rozszerzenie pliku
		if(file_exists($_FILES['uploaded_file']['tmp_name'])){$file_target_location = $target_dir . '/' . $file_name;}
		else{$file_target_location = "";}
		move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], $file_target_location);
		
		$matching_file = mysqli_query($connection, "SELECT * FROM files WHERE file_name='$file_name' AND location='$target_dir'"); // wiersza, w którym login=login z formularza
		$matching_file_rekord = mysqli_fetch_array($matching_file); // wiersza z BD, struktura zmiennej jak w BD
		
		if(!$matching_file_rekord){ //jezeli identyczny plik jeszcze nie istnieje
			$result = mysqli_query($connection, "INSERT INTO files (username, datetime, file_name, file_extension, location) 
			VALUES ('$user', '$datetime', '$file_name', '$file_extension', '$target_dir');") or die ("DB error: $dbname");			
		}
		mysqli_close($connection);
	}
	
	header('Location: index1.php');
?>