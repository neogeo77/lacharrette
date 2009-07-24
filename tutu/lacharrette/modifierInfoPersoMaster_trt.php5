<?php
	require_once "inc_commun.php5";
   	$trigrammePost=$_POST["trigramme"];
   	$utilisateurModif=$calendrier->getPersonne($trigrammePost);

	$utilisateurModif->nom=strtoupper($_POST["nom"]);
	$utilisateurModif->prenom=ucfirst(strtolower($_POST["prenom"]));
	$utilisateurModif->voiture=ucfirst(strtolower($_POST["voiture"]));
	$utilisateurModif->nbrePlaces=$_POST["nbrePlaces"];
	$utilisateurModif->email1=$_POST["email1"];
	$utilisateurModif->telPersonnel=$_POST["telPersonnel"];
	$utilisateurModif->telTravail=$_POST["telTravail"];
	$utilisateurModif->dateArrivee=$_POST["dateArrivee"];
	$utilisateurModif->dateDepart=$_POST["dateDepart"];
	
	$utilisateurModif->enregistrer();
	$calendrier->recharger();
	unset($_SESSION['UTILISATEUR']);
	$_SESSION['CALENDRIER']=$calendrier;
	header('Location: gererMembres.php5');
?>