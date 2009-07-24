<?php
	require_once "inc_commun.php5";
	require_once "class.jourFerie.php5";

	$joursFerie=$_GET['joursFerie'];
	jourFerie::supprimer($joursFerie, $codeTrajet);
	$calendrier->recharger();
	$_SESSION['CALENDRIER']=$calendrier;
	header('Location: gererJoursFeries.php5');
?>