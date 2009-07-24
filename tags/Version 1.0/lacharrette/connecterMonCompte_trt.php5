<?php
	require_once "inc_commun.php5";

	$trigrammeCbo = $_POST["cboPersonne"];
	if ($trigrammeCbo=="ancien") $trigrammeCbo = $_POST["cboPersonneAncienne"];
	
	if ($trigrammeCbo=="")
	{
		header("Location: connecterMonCompte.php5");
		exit;
	}
    $utilisateurCbo=$calendrier->getPersonne($trigrammeCbo);
    
	// Vérification du mot de passe
	$password = $_POST["password"];
	if ((trim(strtolower($utilisateurCbo->password)))!=(trim(strtolower($password))))
	{
		header("Location: connecterMonCompte.php5?motDePasseIncorrect&trigrammeErreur=$trigrammeCbo");
		exit;
	}
	
	// Création du cookie
	$bSouvenir = isset($_POST["chkSouvenir"]);
	if ($bSouvenir)
	{
		setcookie("utilisateur", $trigrammeCbo, time()+3600*24*365);	// enregistrement du cookie
	}
	else
	{
		unset($_COOKIE['utilisateur']);	// suppression du cookie
		setcookie('utilisateur');		// suppression du cookie
	}
	$_SESSION['UTILISATEUR']=$utilisateurCbo;

	$pageCourante=$_SESSION['pageCourante'];
	header("Location: $pageCourante");
?>