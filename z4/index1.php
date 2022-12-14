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
	<style>
		i:hover {
			color: blue;
			cursor: pointer;
		}
	</style>
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
		
		$link = mysqli_connect('', '', '', ''); // połączenie z BD
		if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } // obsługa błędu połączenia z BD
		
		echo $_SESSION['username']; //informacja o tym kto jest zalogowany
		echo "<br>-------------------------------------------------------------------";
		date_default_timezone_set('Europe/Warsaw');

		$username =  $_SESSION['username'];
		
		$main_dir = $username . '/'; //katalog macierzysty zalogowanego uzytkownika
		
		if(!isset($_SESSION['current_dir'])){
			$_SESSION['current_dir'] = $main_dir;
		}
		
		if($_SESSION['current_dir'] == $main_dir){ //jezeli znajdujemy sie w katalogu glownym uzytkownika - mozna tworzyc podkatalogi
	?>
		
			<br><br><form action="index1.php" method="post">
					Nowy katalog: <button type="submit" name="makedir" style="border:none;background-color:#ffffff;">
						<i class="fa-solid fa-folder-plus" type="submit" name="makedir" style="font-size:34px;"></i>
					</button>
			</form>
					
		<?php
		}
				if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['makedir']))
				{
					make_dir();
				}
				function make_dir() { //funkcja do tworzenia nowego katalogu
					echo "Nazwa nowego katalogu<form action='newdir.php' method='post'>
							<input type='text' name='dir_name' maxlength='20' size='20'>
								<input type='submit' value='Send'/>
							</form>";
				}
		?>
		
		<br><br><form action="addfile.php" enctype="multipart/form-data" method="post">
			<input type="file" name="uploaded_file"/><br>
			Wyślij wybrany plik: <button type="submit" name="addfile" style="border:none;background-color:#ffffff;"/><i class="fa-solid fa-cloud-arrow-up" name="addfile" style="font-size:34px;"></i></button>
		</form><br>
		
		<?php echo "<br>Lokalizacja: " . $_SESSION['current_dir'] . "<br>"; ?>
		
		<br><b>Lista katalogów użytkownika:</b>
		<br><br>
		
		<?php if($_SESSION['current_dir'] != $main_dir){ //jezeli nie znajdujemy sie w katalogu glownym uzytkownika - mozna klinkac 'levelup'
		?>
			<form action='levelup.php' method='post'>
					<button type="submit" name="levelup" style="border:none;background-color:#ffffff;">
						<i class="fa-solid fa-reply" style="font-size:24px;"></i>
					</button>
			</form>
		<?php
		}
		
		$current_dir = $_SESSION['current_dir'];
        $current_dir_content = array_filter(glob($current_dir . "*")); //wszystko, co znajduje sie w katalogu glownym
		echo "<a href ='movedir.php?current_dir=$current_dir'>" . $current_dir . "<a><br>"; //glowny katalog uzytkownika
		foreach ($current_dir_content as $content) { //kazdy element katalogu glownego
			if(is_dir($content)){ //jesli element jest katalogiem
				$dir_name = substr($content, strpos($content, "/") + 1);
				echo "&ensp;<a href ='movedir.php?current_dir=$content/'>" . $dir_name . '/' . "</a>"; //&ensp - duza spacja, link do pobrania pliku
				echo "&nbsp&nbsp;<a href='removecontent.php?content_to_remove=$content/'><i class='fa-regular fa-trash-can' style='font-size:14px;'></i></a><br>"; //ikonka kosza
			}
			else{ //jesli element nie jest katalogiem (plik)
				if($current_dir == $main_dir){ //w glownym katalogu
					$file_name = substr($content, strpos($content, "/") + 1);	
				}else{ //w podkatalogach
					$file_name = substr($content, strpos($content, "/") + 1);	
					$file_name = substr($file_name, strpos($file_name, "/") + 1);	
				}
				echo "&ensp;<a href='$content' download>" . $file_name . "</a>"; //link do pobrania pliku
				echo "&nbsp&nbsp;<a href='removecontent.php?content_to_remove=$content'><i class='fa-regular fa-trash-can' style='font-size:14px;'></i></a><br>"; //ikonka kosza	
				$file_extension = substr($content, strpos($content, ".") + 1); //pobranie rozszerzenia
				if($file_extension == "png" || $file_extension == "jpg" || $file_extension == "jpeg" || $file_extension == "gif"){
						//$media_icon = 'fa-regular fa-image'; //ikona - obraz
						echo "&ensp;<img src='$content'><br>";
				}
				else if($file_extension == "mp4"){
						//$media_icon = 'fa-solid fa-video'; //ikona - wideo
						echo "<video controls muted'><source src='$content' type='video/mp4'></video><br>";
				}
				else if($file_extension == "mp3"){
						//$media_icon = 'fa-solid fa-volume-high'; //ikona - audio
						echo "<audio controls><source src='$content' type='audio/mpeg'></audio><br>";
				}
				else{
					echo "&nbsp;Podgląd pliku: <a href='$content'><i class='fa-regular fa-file'></i></a>";
				}						
			}
		}
			
		//informacja o ostatniej probie wlamania sie na konto
		$breakins = mysqli_query($link, "SELECT * FROM break_ins WHERE username='$username' ORDER BY datetime DESC LIMIT 1"); // wiersze, w którym login=login z formularza
		foreach ($breakins as $row) {
			if($row['datetime'] != ""){
				echo "<br><p style='color: red';>Ostatnia próba włamania:<br>DATA: " . $row['datetime'] . "<br>IP: " . $row['ip'] . "</p>";			
			}
		}
		
		mysqli_close($link);
		?>
		
	<br><a href ='logout.php'>Wyloguj</a><br />
</BODY>
</HTML>
