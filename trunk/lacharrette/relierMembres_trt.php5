<?php
	require_once "inc_commun.php5";

	$trigrammeUrl=$_GET['trigramme'];
	$nCorrection=$calendrier->nbreJoursOuvresDepuisDateCompteur();
	$nbreP=$calendrier->moyenneNbreP()-$nCorrection;
	$nbreC=$calendrier->moyenneNbreC();
	$reportNbreC=-$nbreC;
	$reportNbreP=-$nbreP-$nCorrection;
	$ret=personne::relier($trigrammeUrl, $codeTrajet, $nbreC, $nbreP, $reportNbreC, $reportNbreP);
	
	$calendrier->recharger();
	$_SESSION['CALENDRIER']=$calendrier;
	header('Location: gererMembres.php5');
?>