<?php
	require_once "inc_commun.php5";
	require_once "class.jourFerie.php5";

	$jour=$_POST["jour"];
	$mois=$_POST["mois"];
	$annee=$_POST["annee"];
	
	
	if (trim($jour)=="" || trim($mois)=="" || trim($annee)=="")
	{
		header('Location: gererJoursFeries.php5');
		exit;
	}
	
	if (!(is_numeric($jour) && is_numeric($mois) && is_numeric($annee)))
	{
		header('Location: gererJoursFeries.php5');
		exit;
	}

	$jourGlobal=$annee."-".$mois."-".$jour;
	jourFerie::ajouter($jourGlobal, $codeTrajet);
	$calendrier->recharger();
	$_SESSION['CALENDRIER']=$calendrier;
	header('Location: gererJoursFeries.php5');
?>