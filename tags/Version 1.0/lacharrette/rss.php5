<?php Header("content-type: application/xml");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	require_once "class.calendrier.php5";
	require_once "class.trajet.php5";
	require_once "class.personne.php5";
	require_once "class.message.php5";
	require_once "inc_fonctions.php5";
	//session_start();

	$codeTrajet= $_GET["CODETRAJET"];
	$calendrier = new calendrier();
	$calendrier->charger($codeTrajet);
	$codeTrajetLie=$calendrier->trajet->trajetLie;

	if ($codeTrajetLie<>null) 
	{
		$calendrierLie = new calendrier();
		$calendrierLie->charger($codeTrajetLie);
	}
	else
	{
		unset($calendrierLie);
	}

	// Nombre de jours avant et après
	if ($calendrier->estWeekEndOuFerie($calendrier->aujourdhui())) $joursDebut=1; else $joursDebut=0;
	$joursFin=$joursDebut+7;

	$lien="http://www.blueplace.fr/charrette?CODETRAJET=$codeTrajet";
	$titreCharrette=$calendrier->trajet->titre;
	if (isset($calendrierLie))
	{
		$titreCharrette=$titreCharrette.", ".$calendrierLie->trajet->titre;
		$codeTrajet=$calendrier->trajet->codeTrajet;
		$codeTrajetLie=$calendrierLie->trajet->codeTrajet;
	}
	else
	{
		$codeTrajet="";
	}
	
	$xml = "<?xml version='1.0' encoding='iso-8859-1'?><rss version='2.0'>";
	$xml .= "<channel>"; 
	$xml .= "<title>$titreCharrette</title>";
	$xml .= "<link>$lien</link>";
	for ($jour=$joursDebut;$jour<$joursFin;$jour++)
	{
		$date=$calendrier->prochainJourTravaille($jour);
		$nbre=$calendrier->nbrePersonnePourJour($date);
		$titreJour=ucfirst($calendrier->libelleJour($date)).", $nbre personnes";

		$xml .= "<item>";
		$xml .= "<title>$titreJour</title>";
		$xml .= "<link>".$lien."</link>";
		$xml .= "<description><![CDATA[";

		if (isset($calendrierLie)) $xml .= "<b>".$codeTrajet."</b><br>";
		$conducteurs=$calendrier->getConducteurs($date);
		if (sizeof($conducteurs)>0)
		{
			if (sizeof($conducteurs)>1) $xml.="<b>Conducteurs :</b> "; else $xml.="<b>Conducteur :</b> ";
			$sep="";
			foreach ($conducteurs as $pers) {$xml.=$sep.$pers->trigramme;$sep=", ";}
			$xml.="<br>";
		}

		$passagers=$calendrier->getPassagers($date);
		if (sizeof($passagers)>0)
		{
			if (sizeof($passagers)>1) $xml.="<b>Passagers :</b> "; else $xml.="<b>Passager :</b> ";
			$sep="";
			foreach ($passagers as $pers) {$xml.=$sep.$pers->trigramme;$sep=", ";}
			$xml.="<br>";
		}

		$absents=$calendrier->getAbsentsConvenants($date);
		if (sizeof($absents)>0)
		{
			if (sizeof($absents)>1) $xml.="<b>Absents :</b> "; else $xml.="<b>Absent :</b> ";
			$sep="";
			foreach ($absents as $pers) {$xml.=$sep.$pers->trigramme;$sep=", ";}
			$xml.="<br>";
		}

		if (isset($calendrierLie))
		{
			$xml .= "<br><b>".$codeTrajetLie."</b><br>";
			$conducteurs=$calendrierLie->getConducteurs($date);
			if (sizeof($conducteurs)>0)
			{
				if (sizeof($conducteurs)>1) $xml.="<b>Conducteurs :</b> "; else $xml.="<b>Conducteur :</b> ";
				$sep="";
				foreach ($conducteurs as $pers) {$xml.=$sep.$pers->trigramme;$sep=", ";}
				$xml.="<br>";
			}

			$passagers=$calendrierLie->getPassagers($date);
			if (sizeof($passagers)>0)
			{
				if (sizeof($passagers)>1) $xml.="<b>Passagers :</b> "; else $xml.="<b>Passager :</b> ";
				$sep="";
				foreach ($passagers as $pers) {$xml.=$sep.$pers->trigramme;$sep=", ";}
				$xml.="<br>";
			}

			$absents=$calendrierLie->getAbsentsConvenants($date);
			if (sizeof($absents)>0)
			{
				if (sizeof($absents)>1) $xml.="<b>Absents :</b> "; else $xml.="<b>Absent :</b> ";
				$sep="";
				foreach ($absents as $pers) {$xml.=$sep.$pers->trigramme;$sep=", ";}
				$xml.="<br>";
			}
		}

		$xml .= "]]></description>";	
		$xml .= "</item>";	
	}
	$xml .= "</channel>";
	$xml .= "</rss>";

	// Ecriture du flux
	echo $xml;
?>