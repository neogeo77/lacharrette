<?php
	require_once "inc_commun.php5";
	if (!$bEstConnecte || !$utilisateur->estUltraMaster())
	{
		header('Location: index.php5');
		exit;
	}
?>