<?php
	require_once "inc_commun.php5";

	$trigrammePost=$_POST['trigramme'];
	$nbreCSaisi=$_POST['nbreC'];
	$nbrePSaisi=$_POST['nbreP'];
	$reportNbreCSaisi=$_POST['reportNbreC'];
	$reportNbrePSaisi=$_POST['reportNbreP'];

	$aujourdhui=$calendrier->aujourdhui();
	$nbreCActuel=$calendrier->nbreCPourDate($trigrammePost, $aujourdhui);
	$nbrePActuel=$calendrier->nbrePPourDate($trigrammePost, $aujourdhui);

	$compteur=$calendrier->getCompteur($trigrammePost);
	$compteur->nbreC=$compteur->nbreC+$nbreCSaisi-$nbreCActuel;
	$compteur->nbreP=$compteur->nbreP+$nbrePSaisi-$nbrePActuel;
	$compteur->reportNbreC=$reportNbreCSaisi;
	$compteur->reportNbreP=$reportNbrePSaisi;
	$compteur->enregistrer();

	$calendrier->recharger();
	$_SESSION['CALENDRIER']=$calendrier;
	header('Location: gererMembres.php5');
?>