<?php
	require_once "inc_commun.php5";

	$txtTexte=mysqlEscape($_SESSION['texteForum']);
	$_SESSION['texteForum']="";

	$sql = "INSERT INTO messagesforum (texte, trigramme, trajet) VALUES ('$txtTexte', '$trigrammeUser', '$codeTrajet')";
	mysql_query($sql) or die('Erreur SQL !'.$sql.'<br>'.mysql_error());
	header('Location: forum.php5');
?>