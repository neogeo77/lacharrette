<?php
	require_once "inc_commun.php5";

   	$joursAvant=$_SESSION['joursAvant'];
   	$joursApres=$_SESSION['joursApres'];

	for ($jour=$joursAvant;$jour<$joursApres;$jour++)
	{
		$date = calendrier::aujourdhuiPlus($jour);
		if (!$calendrier->estWeekEndOuFerie($date))
		{
			$nomHTML = nomHTML('cbo', $trigrammeUser, $date);
			$code = trim($_POST[$nomHTML]);
			$calendrier->setCode($date, $trigrammeUser, $code);
		}
	}
	$calendrier->enregistrer();
	header('Location: monCompte.php5');
?>