<?php
session_start();
include "polaczenie.php";
include "klasy.php";

if(!isset($_SESSION['zalogowany']))
{
	$_SESSION['zalogowany'] = false;
}

?>
<html>
<head>
	<meta charset="UTF-8">
	<title>Quizer</title> 
	<link rel="stylesheet" type="text/css" href="style.css"/>
	<script src="jquery-2.1.4.min.js"></script>
	<script src="scripts.js"></script>
</head>
<body>

	<?php

		
		if(!empty($_POST))
		{
			pobierz_kategorie();
			if(isset($_POST['login']) && $_POST['login'] != '' )
			{
				logowanie($_POST['login'],$_POST['haslo']);
			}
			if(isset($_POST["Rejestruj"]))
			{	
				if($_POST['rejerehaslo'] === $_POST['rejehaslo'])	
				{
					if(isset($_POST['rejelogin']) && isset($_POST['rejehaslo']) && isset($_POST['rejeemail']) && isset($_POST['rejenick']) && isset($_POST['rejerulez']))
					{	
						rejestracja( $_POST['rejelogin'], $_POST['rejehaslo'], $_POST['rejeemail'], $_POST['rejenick'], $_POST['rejerulez']);
					}
					else
					{
						echo "<script>alert('Niewypelniono w pełni rejestracji!!!');</script>";
					}
				}
				else
				{
					echo "<script>alert('Hasla nie sa zgodne!!!');</script>";
				}
			}	
			if(isset($_POST['pytan1']))
			{
				losuj_jedno_pytanie($_POST['kategoria']);
			}	
			if(isset($_POST['pytan40']))
			{
				losuj_czterdziesci_pytan($_POST['kategoria']);
			}
			if(isset($_POST['wyloguj']) && $_SESSION['zalogowany'])
			{
				wyloguj();				
			}
			if(isset($_POST['DodajPytanie']))
			{
				dodaj_pytanie($_POST['tresc'], $_POST['a'], $_POST['b'], $_POST['c'], $_POST['d'], $_POST['kategoria'], $_POST['poprawna']);
			}
			unset($_POST);
			$_POST = array();
		}
		
		if($_SESSION['zalogowany'])
		{
			$user = unserialize(file_get_contents('store'));			
		}
	?>
	<div id="strona">
		<div id="top">
			<div id="login">
				<?php
				if(!$_SESSION["zalogowany"])
				{
					echo 
					'
					<form action="index.php" method="POST" id="formLogin">
					<table>
					<tr><td>Login: </td><td><input type="text" name="login" size="8"></td>
					<td>Hasło: </td><td><input type="password" name="haslo" size="8"></td>
					<td><input type="submit" value="Zaloguj"></center></td></tr>
					</table>
					</form>
					';
				}
				if($_SESSION["zalogowany"])
				{
					echo '<p class="inline">'.$user->Wys_nick().' &nbsp</p>';
					echo 
					'<form method="post" class="inline">
					<input type="submit" name="wyloguj" value="wyloguj">
					</form>';
				}
				?>
			</div>
		</div>
		<div id="banner">
		Quizer <br> v.0.2a <br>
		Created by Adam Zegadło.
		</div>
			<?php
			if(!$_SESSION["zalogowany"])
			{
				echo
				'<div id="rejestracja">
					<center>Rejestracja</center><br>
					<form action="index.php" method="POST">
						<center><table>
							<tr><td width="200px">Login: </td><td><input type="text" name="rejelogin"></td></tr>
							<tr><td>Hasło: </td><td><input type="password" name="rejehaslo"></td></tr>
							<tr><td>Powtrórz hasło: </td><td><input type="password" name="rejerehaslo"></td></tr>
							<tr><td>E-mail: </td><td><input type="text" name="rejeemail"></td></tr>
							<tr><td>Nazwa wyświetlana:</td><td><input type="text" name="rejenick"></td></tr>
							<tr><td colspan="2"><center><input type="checkbox" name="rejerulez" value="true"><font size="2px">Akceptuje <a href="">regulamin</a></font></center></td></tr>
							<tr><td> &nbsp </td></tr>
							<tr><td colspan="2"><center><input type="submit" name="Rejestruj" value="Zarejestruj"></center></td></tr>
						</table></center>				
					</form>				
				</div>';
			}
			if($_SESSION["zalogowany"])
			{
				echo
				'
				<div id="tresc">
					<div id="wpis">
						<div id="wpis_tytul">Update 0.2a</div>
						<div id="wpis_tresc">Data: 17-01-2016<br/>Poprawa testu 40 pytan, zablokowano pola po sprawdzeniu, dodano kolory dla poprawnych i zlych odpowiedzi.</div>
					</div>
					<div id="wpis">
						<div id="wpis_tytul">Update 0.1a</div>
						<div id="wpis_tresc">Pierwsza w pełni działająca wersja. Przy wyborze 40 pytan trzeba wybrac wszystkie kategorie ponieważ baza danych nie zawiera jeszcze az tylu pytan oraz mechanizmy zapobiegajace błedą tez nie sa jeszcze wprowadzone.</div>
					</div>
					
				</div>
				<div id="menu">
					<center>
						<form id="form1" method="POST" action="wysPytania.php" target="_blank">
						<select name="kategoria">
							<option value="all">Wszystkie</option>
						';
						$kategories = pobierz_kategorie();
						for($i = 0; $i < count($kategories); $i++)
						{
							echo '<option value="'.$kategories[$i].'">'.$kategories[$i].'</option>';
							
						}
						echo					
						'
						</select><br>
						<input type="radio" name="ilePytan" value="1"/> 1 Pytanie <br>
						<input type="radio" name="ilePytan" value="40"/> 40 Pytań <br>
						<input type="submit" name="wyslij" value="Losuj">
						</form>
					</center>
				</div>
				';
				if($user->Return_typ() == 0)
				{
					echo
					'
					<div id="wiecejmenu">
					<button onclick="otworzOkno();">Dodaj pytanie</button>
					<div id="dodajpytanie"><a href="#"><div id="zamknij" onclick="zamknijOkno();">X</div></a>
						<form action="index.php" method="POST">
						Treść pytania:<br/>
						<textarea name="tresc" rows="3" cols="30"></textarea><br/>
						Odpowiedz A: <br/>
						<textarea name="a" rows="2" cols="30"></textarea><br/>
						Odpowiedz B: <br/>
						<textarea name="b" rows="2" cols="30"></textarea><br/>
						Odpowiedz C: <br/>
						<textarea name="c" rows="2" cols="30"></textarea><br/>
						Odpowiedz D: <br/>
						<textarea name="d" rows="2" cols="30"></textarea><br/>
						Poprawna odpowiedz to: <br/>
						<input type="radio" name="poprawna" value="a">A </input>
						<input type="radio" name="poprawna" value="b">B </input>
						<input type="radio" name="poprawna" value="c">C </input>
						<input type="radio" name="poprawna" value="d">D </input><br/>
						Wybierz kategorie pytania: 
						<select name="kategoria">
						';
						$kategories = pobierz_kategorie();
						for($i = 0; $i < count($kategories); $i++)
						{
							echo '<option value="'.$kategories[$i].'">'.$kategories[$i].'</option>';
						}
						echo					
						'<br/>						
						</select><br/><br/>
						<input type="submit" name="DodajPytanie" value="Dodaj pytanie"/>
						</form>
					</div>
					</div>
					';
				}
			}
			?>
		
	</div>
</body>
</html>