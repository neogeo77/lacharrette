<?php
	require_once "inc_commun.php5";

	$utilisateur->password=strtolower($_POST["password"]);
	$utilisateur->enregistrer();
	$calendrier->recharger();
	$_SESSION['CALENDRIER']=$calendrier;
	header('Location: monCompte.php5');
?>