<?php
	require_once "inc_commun.php5";
	setcookie("utilisateur", "", time()+3600*24*365);	// enregistrement du cookie, vide
	unset($_SESSION['UTILISATEUR']);	// suppression de l'utilisateur de la session
	header('Location: monCompte.php5');
?>