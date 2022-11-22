<?php declare(strict_types=1); // włączenie typowania zmiennych w PHP >=7
session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku
if (!isset($_SESSION['loggedin']))
{
	header('Location: logowanie.php');
	exit();
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="fonts/fontawesome/fontawesome/css/all.css">
</head>
<BODY>
	<script type = "text/javascript">
	window.onload = function() {
		if(!window.location.hash) {
			window.location = window.location + '#loaded';
			window.location.reload();
		}
	}
	</script>

	Aplikacja po zalogowaniu (aktualne dane użytkownika) - 
	
	<?php
		error_reporting(0);
		
		$link = mysqli_connect('mariadb106.server701675.nazwa.pl', 'server701675_domdur1', '6D6zB4WuURKzU@h', 'server701675_domdur1'); // połączenie z BD
		if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } // obsługa błędu połączenia z BD
		
		echo $_SESSION['username']; //informacja o tym kto jest zalogowany
		date_default_timezone_set('Europe/Warsaw');

		$username =  $_SESSION['username'];
		
		$main_dir = $username . '/'; //katalog macierzysty zalogowanego uzytkownika
		//$main_dir = $username; //katalog macierzysty zalogowanego uzytkownika
		
		if(!isset($_SESSION['current_dir'])){
			$_SESSION['current_dir'] = $main_dir;
		}
		else{
			echo "<br>Aktualny katalog: " . $_SESSION['current_dir'] . "<br><br>";
		}
		
		if($_SESSION['current_dir'] == $main_dir){ //jezeli znajdujemy sie w katalogu glownym uzytkownika - mozna tworzyc podkatalogi
	?>
		
			<br><form action="index1.php" method="post">
					<button type="submit" name="makedir" style="border:none;background-color:#ffffff;">
						<i class="fa-solid fa-folder-plus" type="submit" name="makedir" style="font-size:34px;"></i>
					</button>
			</form><br>
					
		<?php
		}
				if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['makedir']))
				{
					make_dir();
				}
				function make_dir() { //funkcja do tworzenia nowego katalogu
					echo "<br>Nazwa nowego katalogu<form action='newdir.php' method='post'>
							<input type='text' name='dir_name' maxlength='20' size='20'>
								<input type='submit' value='Send'/>
							</form><br>";
				}
		?>
		
		<label for="uploaded_file">
			<i class="fa-solid fa-cloud-arrow-up" name="addfile" style="font-size:28px;"></i>
		</label>
		
		<form action='addfile.php' method='post' enctype='multipart/form-data'>
			<input type='file' name='uploaded_file' id='uploaded_file' style='display:none;'>
			<input type='submit' name='addfile' id='addfile'><br>
		</form>
		
		<br>Lista katalogów użytkownika:
		<br><br>
		
		<?php if($_SESSION['current_dir'] != $main_dir){ //jezeli nie znajdujemy sie w katalogu glownym uzytkownika - mozna klinkac 'levelup'
		?>
			<form action='levelup.php' method='post'>
					<button type="submit" name="levelup" style="border:none;background-color:#ffffff;">
						<i class="fa-solid fa-circle-arrow-left" type="submit" name="levelup" style="font-size:28px;"></i>
					</button>
			</form>
		<?php
		}
		
		$current_dir = $_SESSION['current_dir'];
        $current_dir_content = array_filter(glob($current_dir . "*")); //wszystko, co znajduje sie w katalogu glownym
		echo "<a href ='movedir.php?current_dir=$current_dir'>" . $current_dir . "<a><br>"; //glowny katalog uzytkownika
		foreach ($current_dir_content as $content) { //kazdy element katalogu glownego
			if(is_dir($content)){ //jesli element jest katalogiem
				echo "&ensp;<a href ='movedir.php?current_dir=$content/'>" . $content . '/' . "</a>"; //&ensp - duza spacja, link do pobrania pliku
				echo "&nbsp&nbsp;<a href='removecontent.php?content_to_remove=$content/'><i class='fa-regular fa-trash-can' style='font-size:14px;'></i></a><br>"; //ikonka kosza
			}
			else{ //jesli element nie jest katalogiem (plik)
				echo "&ensp;<a href='$content' download>" . $content . "</a>"; //link do pobrania pliku
				$file_extension = substr($content, strpos($content, ".") + 1); //pobranie rozszerzenia
				if($file_extension == "png" || $file_extension == "jpg" || $file_extension == "jpeg" || $file_extension == "gif"){
						$media_icon = 'fa-regular fa-image'; //ikona - obraz
				}
				if($file_extension == "mp4"){
						$media_icon = 'fa-solid fa-video'; //ikona - wideo
				}
				if($file_extension == "mp3"){
						$media_icon = 'fa-solid fa-volume-high'; //ikona - audio
				}
				echo "&nbsp;<a href='$content'><i class='$media_icon' style='font-size:14px;'></i></a>";
				echo "&nbsp&nbsp;<a href='removecontent.php?content_to_remove=$content'><i class='fa-regular fa-trash-can' style='font-size:14px;'></i></a><br>"; //ikonka kosza							
			}
		}
		
		//<i class="fa-solid fa-video"></i>
		//<i class="fa-solid fa-volume-high"></i>
			
		//informacja o ostatniej probie wlamania sie na konto
		$breakins = mysqli_query($link, "SELECT * FROM break_ins WHERE username='$username' ORDER BY datetime DESC LIMIT 1"); // wiersze, w którym login=login z formularza
		foreach ($breakins as $row) {
			if($row['datetime'] != ""){
				echo "<p style='color: red';>Ostatnia próba włamania: " . $row['datetime'] . " ip: " . $row['ip'] . "</p>";			
			}
		}
		
		mysqli_close($link);
		?>
		
	<br>
	<a href ='index.php'>Strona główna zadania</a><br />
	<a href ='logout.php'>Wyloguj</a><br />
</BODY>
</HTML>