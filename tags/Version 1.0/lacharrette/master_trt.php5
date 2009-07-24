<?php
	require_once "inc_commun.php5";

   	$joursAvant=$_SESSION['joursAvant'];
   	$joursApres=$_SESSION['joursApres'];
   	$calendrier=$_SESSION['CALENDRIER'];
    $personnes=$calendrier->getPersonnes();
   	
	foreach ($personnes as $personne)
	{
		$trigramme=$personne->trigramme;
		for ($jour=$joursAvant;$jour<$joursApres;$jour++)
		{
			$date = calendrier::aujourdhuiPlus($jour);
			if (!$calendrier->estWeekEndOuFerie($date))
			{
				$nomHTML = nomHTML('hid', $trigramme, $date);
				if (isset($_POST[$nomHTML]))
				{
					$code = trim($_POST[$nomHTML]);
					$calendrier->setCode($date, $trigramme, $code);
				}
			}
		}
	}
	$calendrier->enregistrer();
	header('Location: master.php5');
?>