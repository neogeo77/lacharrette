<?php
	require_once "inc_commun.php5";
	$message=$_SESSION['MESSAGECM'];
	if (isset($_POST["boolDefaut"])) $boolDefaut="O"; else $boolDefaut="N";
	$message->texte=$_POST["txtMessage"];
	$message->boolDefaut=$boolDefaut;
	$message->enregistrer();
	header('Location: master.php5');
?>