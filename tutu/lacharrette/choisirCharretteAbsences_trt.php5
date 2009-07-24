<?php
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	session_start();
	$codeTrajet = $_GET["trajet"];
	
	// Création du cookie
	setcookie("CODETRAJET", $codeTrajet, time()+3600*24*365);	// enregistrement du cookie
	$_SESSION['CODETRAJET']=$codeTrajet;
	unset($_SESSION['UTILISATEUR']);
	unset($_SESSION['CALENDRIER']);
	header("Location: absences.php5");
?>