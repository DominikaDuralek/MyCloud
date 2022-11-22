<?php
	//skrypt do usuwania plikow i katalogow
	session_start();
	$content_to_remove = $_GET['content_to_remove'];
	if(is_dir($content_to_remove)){ //jesli element do usuniecia jest katalogiem
	    $dir_content = array_filter(glob($content_to_remove . "*"));  //elementy wewnatrz katalogu do usuniecia
		foreach ($dir_content as $content) {
			unlink($content);	
			$link = mysqli_connect('mariadb106.server701675.nazwa.pl', 'server701675_domdur1', '6D6zB4WuURKzU@h', 'server701675_domdur1'); // połączenie z BD
			if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } // obsługa błędu połączenia z BD
			$sql = "DELETE FROM files WHERE concat(location, file_name) = '$content'"; //usuniecie pliku z bazy
			mysqli_query($link, $sql);
			mysqli_close($link);
		}
		rmdir($content_to_remove); //usuniecie katalogu po usunieciu jego plikow
	}
	else{ //element do usuniecia jest plikiem
		unlink($content_to_remove);	
		$link = mysqli_connect('mariadb106.server701675.nazwa.pl', 'server701675_domdur1', '6D6zB4WuURKzU@h', 'server701675_domdur1'); // połączenie z BD
		if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } // obsługa błędu połączenia z BD
		$sql = "DELETE FROM files WHERE concat(location, file_name) = '$content_to_remove'"; //usuniecie pliku z bazy
		mysqli_query($link, $sql);
		mysqli_close($link);
	}
	header('Location: index1.php');
?>