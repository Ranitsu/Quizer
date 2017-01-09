<html>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="style.css"/>
	<script src="jquery-2.1.4.min.js"></script>
	<script src="scripts.js"></script>
	<title>Quizer</title>
</head>
<body style="width:578px; margin-left: auto; margin-right: auto;">
<div id="wynik"> </div>
<?php	
	session_start();
	include "polaczenie.php";
	
	$ilePytan = null;
	
	if(isset($_POST['ilePytan']) && $_POST['ilePytan'] == "40")
	{
		$ilePytan = 40;
	}
	else
	{
		$ilePytan = 1;
	}
	
	$kategoriapost = $_POST['kategoria'];
	
	$tabPyt = pobierz_liczbe_pytan($kategoriapost);
	$liczbaPytanWKat = count($tabPyt);
	//echo $liczbaPytanWKat;

	if($ilePytan == 1)
	{
		losuj_jedno_pytanie($kategoriapost);
	}
	else if($ilePytan == 40 && $liczbaPytanWKat > 40)
	{	
		losuj_czterdziesci_pytan($kategoriapost);
		echo '<div id="czas"><script>getStartTime();minutnik2()</script> </div>';
	}
	else
	{
		echo 'Nie ma odpowiedniej liczby pytan w tej kategorii.';
	}
?>
</body>
</html>