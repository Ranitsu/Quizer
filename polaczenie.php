<?php
//include "klasy.php";

$servername = "localhost";
$username = "root";
$pass = "";
$dbname = "quizer";

function rejestracja($login, $haslo, $email, $nick, $rules)
{
	if($rules)
	{
		// Utworzenie polaczenia
		$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['pass'], $GLOBALS['dbname']);
		$conn->set_charset("utf8");	
			
		// Sprawdzenie połaczenia
		if ($conn->connect_error)
		{
			die("Connection failed: " . $conn->connect_error);
		}	
		$hhaslo = md5($haslo);
		// Utworzenie zapytanie
		$sql = "INSERT INTO users(login, password, email, nick) VALUES ('".$login."', '".$haslo."', '".$email."', '".$nick."')";
				
		if($conn->query($sql) === true)	
		{
			echo "<script>alert('Rejestracja wykonana');</script>";
		}
		else
		{
			echo "Error: ". $conn->error;
		}
		$conn->close();
	}	
	else
	{
		 echo ("Nie zaakceptowano regulaminu!");
	}
}

function logowanie($login, $haslo)
{
	// Create connection
	$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['pass'], $GLOBALS['dbname']);
	$conn->set_charset("utf8");
	
	// Check connection
	if ($conn->connect_error)
	{
		die("Connection failed: " . $conn->connect_error);
	}
	
	$sql = "SELECT * FROM users WHERE login LIKE '".$login."'";
	$result = $conn->query($sql);
	
	if($result->num_rows > 0);
	{
		$row = $result->fetch_assoc();
		//$hhaslo = md5($haslo);
		if($row["password"] == $haslo)
		{
			$user = new User($row["id_user"], $row["login"], $row["password"], $row["email"], $row["nick"], $row["typ_konta"]);
			$userser = serialize($user);
			//$_SESSION['user'] = $userser;
			file_put_contents('store', $userser);
			
			/*$_SESSION['id'] = $row["id_user"];
			$_SESSION['login'] = $row["login"];
			$_SESSION['haslo'] = $row["password"];
			$_SESSION['email'] = $row["email"];
			$_SESSION['nick'] = $row["nick"];
			$_SESSION['typ_konta'] = $row["typ_konta"];*/
			$_SESSION['zalogowany'] = true;
		}	
	}
		
	$conn->close();
}

function pobierz_liczbe_pytan($kategoria)
{
	$id_pytan = Array();
	
	// Create connection
	$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['pass'], $GLOBALS['dbname']);
	$conn->set_charset("utf8");
	
	// Check connection
	if ($conn->connect_error)
	{
		die("Connection failed: " . $conn->connect_error);
	}
	
	if($kategoria == "all")
	{
		$sql = "SELECT id_pytania FROM pytania";
	}
	else
	{
		$sql = "SELECT id_pytania FROM pytania
		INNER JOIN kategorie_pytan ON kategorie_pytan.id_kategorii = pytania.kategoria
		WHERE kategorie_pytan.nazwa = '".$kategoria."'";
	}
	
	$result = $conn->query($sql);
	
	if($result->num_rows > 0);
	{
		while($row = $result->fetch_assoc())
		{
			$id_pytan[] = $row["id_pytania"];
		}			
	}
	
	return $id_pytan;
}

function losuj_jedno_pytanie($kategoriapost)
{
	$id_pytan = Array();
	$tresc_pytania;
	$a; $b; $c; $d;
	$prawidlowa;
	$kategoria;
	$id_pytan = pobierz_liczbe_pytan($kategoriapost);
	
	// Create connection
	$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['pass'], $GLOBALS['dbname']);
	$conn->set_charset("utf8");
	
		
	//$losowe_pytanie = mt_rand(1, pobierz_liczbe_pytan($kategoriapost));
	$id = array_rand($id_pytan);
	$losowe_pytanie = $id_pytan[$id];
	//echo $losowe_pytanie;
	//echo $kategoriapost;
	
	if($kategoriapost == "all")
	{
		$sql = "SELECT * FROM pytania WHERE id_pytania LIKE ".$losowe_pytanie."";
	}
	else
	{
		$sql = "SELECT pytania.* FROM pytania
		INNER JOIN kategorie_pytan ON pytania.kategoria = kategorie_pytan.id_kategorii
		WHERE pytania.id_pytania = ".$losowe_pytanie." AND kategorie_pytan.nazwa = '".$kategoriapost."'";
	}
	$result = $conn->query($sql);
	
	if($result->num_rows > 0);
	{
		$row = $result->fetch_assoc();
		$tresc_pytania = $row["tresc"];
		$a = $row["odp_a"];
		$b = $row["odp_b"];
		$c = $row["odp_c"];
		$d = $row["odp_d"];
		$poprawna = $row["poprawna"];
		$kategoria = $row["kategoria"];
		$zalacznik = $row["zalacznik"];
	}
	
	$conn->close();
	

	echo
	'
		<div id=\'pytanie\'><form name=\'form2\' id=\'form2\' method=\'POST\' action=\'\'>
		<div id=\'tresc_pytania\'>'.$tresc_pytania.'</div>
		<input type=\'hidden\' name=\'poprawna1\' value=\''.$poprawna.'\'>
		<div id=\'odpowiedz\'><input type=\'radio\' name=\'odp1\' value=\'a\' >A: '.$a.'</input></div>
		<div id=\'odpowiedz\'><input type=\'radio\' name=\'odp1\' value=\'b\' >B: '.$b.'</input></div>
		<div id=\'odpowiedz\'><input type=\'radio\' name=\'odp1\' value=\'c\' >C: '.$c.'</input></div>
		<div id=\'odpowiedz\'><input type=\'radio\' name=\'odp1\' value=\'d\' >D: '.$d.'</input></div>
		<div id=\'idpytania\'>id:'.$losowe_pytanie.'</div><br>';						
		if( $zalacznik != "")
		{
			echo '<center><img src="/Quizer/images/'.$zalacznik.'.jpg"></center><br>';
		}
	
	echo '<center><input type="submit" name="pytan1odp" value="Sprawdz" onclick="sprawdz();"></center></form></div>';
}

function losuj_czterdziesci_pytan($kategoriapost)
{
	$id_pytan = Array();
	$tresc_pytania =  Array();
	$a = Array(); 
	$b = Array(); 
	$c = Array(); 
	$d = Array();
	$prawidlowa = Array();
	$kategoria = Array();
	$id_pytan = pobierz_liczbe_pytan($kategoriapost);

	$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['pass'], $GLOBALS['dbname']);
	$conn->set_charset("utf8");
	
		
	//$losowe_pytanie = mt_rand(1, pobierz_liczbe_pytan($kategoriapost));
	
	$klucze_pytan = array_rand($id_pytan, 40);
	
	$id_losowych_pytan = Array();
	for($i = 0; $i < 40; $i++)
	{
		$id_losowych_pytan[$i] = $id_pytan[$klucze_pytan[$i]];
	}

	$newarray = implode(", ", $id_losowych_pytan);
	//echo $newarray;
	//echo "<br>";
		
	if($kategoriapost = "all")
	{
		$sql = "SELECT * FROM pytania WHERE id_pytania IN (".$newarray.")";
	}
	else
	{
		$sql = "SELECT pytania.* FROM pytania
		INNER JOIN kategorie_pytan ON pytania.kategoria = kategorie_pytan.id_kategorii
		WHERE pytania.id_pytania IN ".$newarray." AND kategorie_pytan.nazwa = '".$kategoriapost."'";
	}
	$result = $conn->query($sql);
	
	if($result->num_rows > 0);
	{
		While($row = $result->fetch_assoc())
		{
			$tresc_pytania[] = $row["tresc"];
			$a[] = $row["odp_a"];
			$b[] = $row["odp_b"];
			$c[] = $row["odp_c"];
			$d[] = $row["odp_d"];
			$poprawna[] = $row["poprawna"];
			$kategoria[] = $row["kategoria"];
			$zalacznik[] = $row["zalacznik"];
		}
	}
	
	$conn->close();
	

	echo '<div id="pytanie"><form id="form2" method="POST" action="">';
	for($i = 0; $i < 40; $i++)
	{
		echo '<div id="tresc_pytania">' .($i+1). ". " .$tresc_pytania[$i].'</div>
			<input type="hidden" name="poprawna'.$i.'" value="'.$poprawna[$i].'">
			<div id="odpowiedz"><input type="radio" name="odp'.$i.'" value="a"/>A:&nbsp'.$a[$i].'</div>
			<div id="odpowiedz"><input type="radio" name="odp'.$i.'" value="b"/>B:&nbsp'.$b[$i].'</div>
			<div id="odpowiedz"><input type="radio" name="odp'.$i.'" value="c"/>C:&nbsp'.$c[$i].'</div>
			<div id="odpowiedz"><input type="radio" name="odp'.$i.'" value="d"/>D:&nbsp'.$d[$i].'</div>';
		if($zalacznik[$i] != '')
		{
			echo '<br/><center><img src="/Quizer/images/'.$zalacznik[$i].'.jpg"></center>';
		}
		echo '<div id=\'idpytania\'>id:'.$id_losowych_pytan[$i].'</div><br>';
	}	
	echo '
	<center><input type="submit" name="pytan40odp" value="Sprawdz" onclick="sprawdz40();"></center></form></div>';
}

function wyloguj()
{
	$_SESSION['zalogowany'] = false;
	session_destroy();
}

function pobierz_kategorie()
{
	$kategoriein = array();

	// Create connection
	$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['pass'], $GLOBALS['dbname']);
	$conn->set_charset("utf8");
	
	// Check connection
	if ($conn->connect_error)
	{
		die("Connection failed: " . $conn->connect_error);
	}
	
	$sql = "SELECT nazwa FROM kategorie_pytan";
	$result = $conn->query($sql);
	
	if($result->num_rows > 0);
	{
		while($row = $result->fetch_assoc())
		{
			$kategoriein[] = $row["nazwa"];
			//echo $row["nazwa"]. "&nbsp";
		}
	}
	
	//echo count($kategoriein);
	return $kategoriein;
}


function dodaj_pytanie($tresc,$a,$b,$c,$d,$kategoria, $poprawna)
{
	$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['pass'], $GLOBALS['dbname']);
	$conn->set_charset("utf8");	
	
	if($conn->connect_error)
	{
		die("Connection Error");
	}
	
	$sql = "SELECT * FROM kategorie_pytan WHERE nazwa = '".$kategoria."'";
	
	$result = $conn->query($sql);
	
	if($result->num_rows > 0)
	{
		while( $row = $result->fetch_assoc())
		{
			$kategoria = $row["id_kategorii"];
		}
	}
	else
	{
		echo "Blad";
	}
	
	$sql = "INSERT INTO pytania(tresc, odp_a, odp_b, odp_c, odp_d, poprawna, kategoria) VALUES ('".$tresc."', '".$a."', '".$b."', '".$c."', '".$d."', '".$poprawna."', '".$kategoria."')";
	
	if($conn->query($sql) === TRUE)
	{
		echo "<script>alert('Dodano pytanie do bazy.');</script>";
	}
	
	$conn->close();	
	header( "refresh:5;url=index.php" );
}

/*
		$row = $result->fetch_assoc();
		$_SESSION['id'] = $row["id"];
		$_SESSION['login'] = $row["id"];
		$_SESSION['haslo'] = $row["haslo"];
		$_SESSION['email'] = $row["email"];
		$_SESSION['nick'] = $row["nick"];
		$_SESSION['grupa'] = $row["grupa"];
		*/

?>