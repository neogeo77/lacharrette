<?php
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	session_start();
	setcookie("CODETRAJET", "", time()+3600*24*365);	// enregistrement du cookie, vide
	unset($_SESSION['CODETRAJET']);	// suppression de l'utilisateur de la session
	header('Location: choisirCharrette.php5');
?>