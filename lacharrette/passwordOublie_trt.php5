<?php
	require_once "inc_commun.php5";
	$trigrammeCbo = $_POST["cboPersonne"];
	$adresseMail=$calendrier->getPersonne($trigrammeCbo)->email1;
	$password=$calendrier->getPersonne($trigrammeCbo)->password;
	mail($adresseMail, "Votre mot de passe Charrette", "Le voici : $password", "From: postmaster@blueplace.fr\nReturn-path: postmaster@blueplace.fr\nX-Mailer: PHP");
	header("Location: passwordOublieFin.php5");
?>