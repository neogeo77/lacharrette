<?php
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	require_once "class.calendrier.php5";
	require_once "class.personne.php5";
	require_once "class.message.php5";
	require_once "inc_fonctions.php5";
	require_once "class.html.php5";
	session_start();

	if (isset($_GET["CODETRAJET"]))
	{
		$codeTrajet= $_GET["CODETRAJET"];
		$_SESSION["CODETRAJET"]=$codeTrajet;
	}
	elseif (isset($_COOKIE["CODETRAJET"]) && ($_COOKIE["CODETRAJET"]!=""))
	{
		$codeTrajet= $_COOKIE["CODETRAJET"];
		$_SESSION["CODETRAJET"]=$codeTrajet;
	}
	else
	{
		header('Location: choisirCharrette.php5');
		exit;
	}
	$codeTrajet=$_SESSION['CODETRAJET'];

	$calendrier = new calendrier();
	$calendrier->charger($codeTrajet);
	$_SESSION['CALENDRIER']=$calendrier;
   	$calendrier=$_SESSION['CALENDRIER'];
	
	// Nombre de jours avant et après
	$joursDebut=0;
	$joursFin=$joursDebut+7;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<meta http-equiv="imagetoolbar" content="no">
<link rel="icon" type="image/png" href="img/icone.png" />
<link rel="shortcut icon" type="image/x-icon" href="img/icone.ico" />
<LINK media="screen" href="style.css" type="text/css" rel="stylesheet">
</HEAD>
<BODY style="BACKGROUND:#FFFFFF;">

<?php
//***************** Calendrier *****************
html::htmlCalendrier($calendrier, $joursDebut, $joursFin, true, false);
echo "<br>";
//***************** Récapitulatif jour suivant *****************
html::htmlMessage($calendrier, 265);
echo "<br>";

//***************** Charrette liée éventuellement *****************
$codeTrajetLie=$calendrier->trajet->trajetLie;
if ($codeTrajetLie<>null) 
{
	$calendrierLie = new calendrier();
	$calendrierLie->charger($codeTrajetLie);
	html::htmlCalendrier($calendrierLie, $joursDebut, $joursFin, true, false);
	echo "<br>";
	html::htmlMessage($calendrierLie, 265);
}
?>
</body>
</html>
