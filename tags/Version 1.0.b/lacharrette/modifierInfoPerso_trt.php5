<?php
	require_once "inc_commun.php5";

	$utilisateur->nom=strtoupper($_POST["nom"]);
	$utilisateur->prenom=ucfirst(strtolower($_POST["prenom"]));
	$utilisateur->voiture=ucfirst(strtolower($_POST["voiture"]));
	$utilisateur->nbrePlaces=$_POST["nbrePlaces"];
	$utilisateur->email1=$_POST["email1"];
	$utilisateur->telPersonnel=$_POST["telPersonnel"];
	$utilisateur->telTravail=$_POST["telTravail"];
	$utilisateur->dateArrivee=$_POST["dateArrivee"];
	$utilisateur->dateDepart=$_POST["dateDepart"];
	
	$utilisateur->enregistrer();
	$calendrier->recharger();
	unset($_SESSION['UTILISATEUR']);
	$_SESSION['CALENDRIER']=$calendrier;
	header('Location: monCompte.php5');
?>