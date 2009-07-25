<?php
	require_once "inc_commun.php5";

   	$joursAvant=$_SESSION['joursAvant'];
   	$joursApres=$_SESSION['joursApres'];
   	$trigrammeAbsence=$_POST["trigramme"];

	for ($jour=$joursAvant;$jour<$joursApres;$jour++)
	{
		$date = calendrier::aujourdhuiPlus($jour);
		if (!$calendrier->estWeekEndOuFerie($date))
		{
			$nomHTML = nomHTML('cbo', $trigrammeAbsence, $date);
			$code = trim($_POST[$nomHTML]);
			$calendrier->setCode($date, $trigrammeAbsence, $code);
		}
	}
	$calendrier->enregistrer();
	header('Location: master.php5');
?>