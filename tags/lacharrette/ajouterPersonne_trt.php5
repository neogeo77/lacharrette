<?php
	require_once "inc_commun.php5";

	$newUtilisateur=new personne;
	$newUtilisateur->trigramme=strtoupper($_POST["trigramme"]);
	$newUtilisateur->password=strtolower($_POST["password"]);
	$newUtilisateur->nom=strtoupper($_POST["nom"]);
	$newUtilisateur->prenom=ucfirst(strtolower($_POST["prenom"]));
	$newUtilisateur->voiture=ucfirst(strtolower($_POST["voiture"]));
	$newUtilisateur->nbrePlaces=$_POST["nbrePlaces"];
	$newUtilisateur->email1=$_POST["email1"];
	$newUtilisateur->telPersonnel=$_POST["telPersonnel"];
	$newUtilisateur->telTravail=$_POST["telTravail"];
	$newUtilisateur->dateArrivee=$_POST["dateArrivee"];

	$nCorrection=$calendrier->nbreJoursOuvresDepuisDateCompteur();
	$nbreP=$calendrier->moyenneNbreP()-$nCorrection;
	$nbreC=$calendrier->moyenneNbreC();
	$reportNbreC=-$nbreC;
	$reportNbreP=-$nbreP-$nCorrection;
	$ret=$newUtilisateur->ajouter($codeTrajet, $nbreC, $nbreP, $reportNbreC, $reportNbreP);
	
	if ($ret=="OK")
	{
		// Aucune erreur
		$calendrier->recharger();
		$_SESSION['CALENDRIER']=$calendrier;
		header('Location: gererMembres.php5');
	}
	else
	{
		// Erreur
		$_SESSION['NEW_UTILISATEUR']=$newUtilisateur;
		$_SESSION['ERREUR_SQL']=$ret;
		header('Location: ajouterPersonne.php5');
	}
?>