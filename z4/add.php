<?php
$user = htmlentities ($_POST['user'], ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej $user
$pass = htmlentities ($_POST['pass'], ENT_QUOTES, "UTF-8"); // rozbrojenie potencjalnej bomby w zmiennej $pass
$confirm_pass = htmlentities ($_POST['confirm_pass'], ENT_QUOTES, "UTF-8");
$link = mysqli_connect('mariadb106.server701675.nazwa.pl', 'server701675_domdur1', '6D6zB4WuURKzU@h', 'server701675_domdur1'); // połączenie z BD
if(!$link) { echo"Błąd: ". mysqli_connect_errno()." ".mysqli_connect_error(); } // obsługa błędu połączenia z BD
mysqli_query($link, "SET NAMES 'utf8'"); // ustawienie polskich znaków

$result = mysqli_query($link, "SELECT * FROM users WHERE username='$user'"); // wiersza, w którym login=login z formularza
$rekord = mysqli_fetch_array($result); // wiersza z BD, struktura zmiennej jak w BD

if($pass !== $confirm_pass) //czy hasło zostało wpisane poprawnie 2 razy
{
	echo "Hasła niezgodne!";
	echo "<br /><a href ='rejestruj.php'>Rejestracja</a>";
}
else
{
	if(!$rekord) //jeśli użytkownik o podanym loginie nie istnieje
	{
		$sql = "INSERT INTO users (username, password) VALUES ('$user', '$pass')";
		if (mysqli_query($link, $sql)) 
		{
			if (!file_exists($user . '/')) {
				mkdir($user . '/', 0777, true);
			}
			header('Location: logowanie.php');
		}else 
		{
			header('Location: rejestracja.php');
			die("Błąd przy dodawaniu użytkownika do bazy!");
		}
	}else //jeśli podany login już istnieje
	{
		echo "Użytkownik już istnieje!"; ?>
		<a href="rejestruj.php">Poprzednia strona</a>
		<?php
	}
}
mysqli_close($link);
?>