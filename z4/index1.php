<?php declare(strict_types=1); // włączenie typowania zmiennych w PHP >=7
session_start(); // zapewnia dostęp do zmienny sesyjnych w danym pliku
if (!isset($_SESSION['loggedin']))
{
	header('Location: index12.php');
	exit();
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
		echo $_SESSION['username']; //informacja o tym kto jest zalogowany
		date_default_timezone_set('Europe/Warsaw');

		$ipaddress = $_SERVER["REMOTE_ADDR"];
		$json = file_get_contents ("http://ipinfo.io/{$ipaddress}/geo");
		$details = json_decode ($json); //informacje na podstawie adresu ip goscia
		$loc = $details -> loc; //informacja o lokalizacji
		$datetime = date('Y-m-d H:i:s');
		function get_browser_name() { 
		  $user_agent = $_SERVER['HTTP_USER_AGENT'];
		  $name = 'Unknown';
		  if(preg_match('/Opera/i',$user_agent)) {
			$name = 'Opera';
		  }elseif(preg_match('/Edg/i',$user_agent)) {
			$name = 'Edge';
		  }elseif(preg_match('/Chrome/i',$user_agent)) {
			$name = 'Chrome';
		  }elseif(preg_match('/Safari/i',$user_agent)) {
			$name = 'Safari';
		  }elseif(preg_match('/Firefox/i',$user_agent)) {
			$name = 'Mozilla Firefox';
		  }elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) {
			$name =  'Internet Explorer';
		  }
			return $name;
		}
		$browser_name = get_browser_name(); //nazwa przegladarki

		$screen_width = "<script>document.write(screen.width);</script>";
		$screen_height = "<script>document.write(screen.height);</script>";
		$browser_width = "<script>document.write(window.innerWidth);</script>";
		$browser_height = "<script>document.write(window.innerHeight);</script>";
		$screen_colors = "<script>document.write(screen.colorDepth);</script>";
		$cookies_enabled = "<script>document.write(navigator.cookieEnabled);</script>";
		$java_enabled = "<script>document.write(navigator.javaEnabled());</script>";
		$browser_language = "<script>document.write(navigator.language);</script>";

		$link = mysqli_connect('mariadb106.server701675.nazwa.pl', 'server701675_domdur', '6D6zB4WuURKzU@h', 'server701675_domdur'); // połączenie z BD	
		if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } // obsługa błędu połączenia z BD

		$username =  $_SESSION['username'];
		$result = mysqli_query($link, "SELECT * FROM goscieportalu"); // wiersze, w którym login=login z formularza
		$rekord = mysqli_fetch_array($result); // wiersza z BD, struktura zmiennej jak w BD
		
		//aktualne dane odwiedzającego
		echo "<table border='1'>
		<tr>
		<th>ipaddress</th>
		<th>datetime</th>
		<th>country</th>
		<th>city</th>
		<th>google maps</th>
		<th>browser</th>
		<th>screen resolution</th>
		<th>browser resolution</th>
		<th>colors</th>
		<th>cookies enabled</th>
		<th>java enabled</th>
		<th>language</th>
		</tr>";
			echo "<tr>";
			echo "<td>" .$ipaddress."</td>";
			echo "<td>" .$datetime."</td>";
			echo "<td>" .$details -> country."</td>";
			echo "<td>" .$details -> city."</td>";
			echo "<td>"."<a href='https://www.google.pl/maps/place/$loc' target='_blank'>LINK</a>"."</td>";
			echo "<td>" .$browser_name."</td>";
			echo "<td>" .$screen_width."x".$screen_height."</td>";
			echo "<td>" .$browser_width."x".$browser_height."</td>";
			echo "<td>" .$screen_colors."</td>";
			echo "<td>" .$cookies_enabled."</td>";
			echo "<td>" .$java_enabled."</td>";
			echo "<td>" .$browser_language."</td>";
		echo "</table><br />";
		
		echo "<a href ='index.php'>Strona główna zadania</a><br />";
		echo "<a href ='board.php'>Komunikaty użytkownika</a><br />";
		
		//dane o poprzednich zalogowaniach
		echo "<br />Poprzednie zalogowania użytkowników:<br />";
		
		echo "<table border='1'>
		<tr>
		<th>ipaddress</th>
		<th>datetime</th>
		<th>username</th>
		<th>browser</th>
		<th>screen resolution</th>
		<th>browser resolution</th>
		<th>colors</th>
		<th>cookies enabled</th>
		<th>java enabled</th>
		<th>browser language</th>
		</tr>";
		
		foreach ($result as $row) { //wyswietlenie wszystkich odpowiednich rekordow z bazy
			echo "<tr>";
			echo "<td>" .$row['ipaddress']."</td>";
			echo "<td>" .$row['datetime']."</td>";
			echo "<td>" .$row['username']."</td>";
			echo "<td>" .$row['browser']."</td>";
			echo "<td>" .$row['screen_resolution']."</td>";
			echo "<td>" .$row['browser_resolution']."</td>";
			echo "<td>" .$row['colors']."</td>";
			echo "<td>" .$row['cookies_enabled']."</td>";
			echo "<td>" .$row['java_enabled']."</td>";
			echo "<td>" .$row['browser_language']."</td>";	
		}
		echo "</table><br />";

		mysqli_close($link);
	?>
	
	<a href ="logout.php">Wyloguj</a><br />
</BODY>
</HTML>