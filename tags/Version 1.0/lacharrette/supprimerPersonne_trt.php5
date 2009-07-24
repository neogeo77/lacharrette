<?php
	require_once "inc_commun.php5";

	$trigrammeUrl=$_GET['trigramme'];
	$user=$calendrier->getPersonne($trigrammeUrl);
	$user->delier($codeTrajet);
	$calendrier->modifierCompteursDeDepart($trigrammeUrl);
	$calendrier->recharger();
	$_SESSION['CALENDRIER']=$calendrier;
	header('Location: gererMembres.php5');
?>