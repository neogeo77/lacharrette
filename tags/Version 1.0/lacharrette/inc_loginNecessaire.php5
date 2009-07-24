<?php
	require_once "inc_commun.php5";
	if (!$bEstConnecte)
	{
		$_SESSION['pageCourante']=$_SERVER['PHP_SELF'];		// nom de la page courante (exemple: "master.php5") La mémoriser permet de revenir sur cette page une fois d'identification effectuée
		header('Location: connecterMonCompte.php5');
		exit;
	}
?>