<?php
	function couleurJourHTMLFutur($code)
	{
		$date=time()+3600;
		return couleurJourHTML($code, $date);
	}	
   	
	function couleurJourHTML($code, $date)
	{
		if ($date<time()) $couleur=couleurPasse($code); else $couleur=couleurFutur($code);
		return $couleur;
	}

	function couleurFutur($code)
	{
		$couleur="#FFFFFF";
		if ($code=="C") $couleur="#CCFFCC";
		if ($code=="P") $couleur="#FFFF99";
		if ($code=="A") $couleur="#EB7373";
		if ($code=="O") $couleur="#EB7373";
		if ($code=="V") $couleur="#CCFFCC";
		if ($code=="I") $couleur="#FFFF99";
		if ($code=="-") $couleur="#FFFF99";
		return $couleur;
	}

	function couleurPasse($code)
	{
		$couleur="#FFFFFF";
		if ($code=="C") $couleur="#E3FFE3";
		if ($code=="P") $couleur="#FFFECF";
		if ($code=="A") $couleur="#F9D5D5";
		if ($code=="O") $couleur="#F9D5D5";
		if ($code=="V") $couleur="#E3FFE3";
		if ($code=="I") $couleur="#FFFECF";
		return $couleur;
	}

	function nomHTML($prefix, $trigramme, $date)
	{
		return $prefix.$trigramme.date("Ymd", $date);
	}
	
	//
	// Renvoie 'Jan', 'Fev', 'Mar'... selon le numéro du mois
	// Remarque : l'utilisation de "setlocale" pour obtenir les mois en francais (janvier, février...) ne fonctionne pas sur certains serveurs. D'où la traduction "manuelle".
	//
	$tmois=array('Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jui', 'Aou', 'Sep', 'Oct', 'Nov', 'Déc');
	function nomDuMoisAbrege($nMois)
	{
		global $tmois;
		return $tmois[$nMois-1];
	}

	//
	// Renvoie 'lun', 'mar'... selon la date
	//
	function nomDuJourAbrege($date)
	{
		return substr(nomDuJour($date), 0,3);
	}

	//
	// Renvoie 'lundi', 'mardi'... selon la date
	//
	$traduire=array('monday'=>'lundi', 'tuesday'=>'mardi', 'wednesday'=>'mercredi', 'thursday'=>'jeudi', 'friday'=>'vendredi', 'saturday'=>'samedi', 'sunday'=>'dimanche');
	function nomDuJour($date)
	{
		global $traduire;
		setlocale(LC_TIME, "en");
		$jour=strtolower(strftime("%A", $date));
		// L'utilisation de "setlocale" pour obtenir les jours en francais (lundi, mardi...) ne fonctionne pas sur certains serveurs. D'où la traduction "manuelle":
		$jour=$traduire[$jour];
		return $jour;
	}
	
	// Retourne le nombre de jour dans un mois
	function nombreJourDansMois($mois, $annee)
	{
		$n = date("j", mktime(0, 0, 0, $mois + 1, 1, $annee) - 1 );
		return $n;
	}

	//
	// Remplacement pour les emoticones
	//
	function emoticone($message)
	{ 
		$debut="<IMG src='img/emoticones/";
		$fin="'>";
		$message = str_ireplace(":)",    $debut."1.gif".$fin, $message);
		$message = str_ireplace(":-)",   $debut."1.gif".$fin, $message);
		$message = str_ireplace(":D",    $debut."2.gif".$fin, $message);
		$message = str_ireplace(";)",    $debut."3.gif".$fin, $message);
		$message = str_ireplace(":-O",   $debut."4.gif".$fin, $message);
		$message = str_ireplace(":P",    $debut."5.gif".$fin, $message);
		$message = str_ireplace("(H)",   $debut."6.gif".$fin, $message);
		$message = str_ireplace(":@",    $debut."7.gif".$fin, $message);
		$message = str_ireplace(":S",    $debut."8.gif".$fin, $message);
		$message = str_ireplace(":$",    $debut."9.gif".$fin, $message);
		$message = str_ireplace(":(",    $debut."10.gif".$fin, $message);
		$message = str_ireplace(":'(",   $debut."11.gif".$fin, $message);
		$message = str_ireplace(":|",    $debut."12.gif".$fin, $message);
		$message = str_ireplace("(A)",   $debut."13.gif".$fin, $message);
		$message = str_ireplace("8o|",   $debut."14.gif".$fin, $message);
		$message = str_ireplace("8-|",   $debut."15.gif".$fin, $message);
		$message = str_ireplace("+o(",   $debut."16.gif".$fin, $message);
		$message = str_ireplace("(:o)",  $debut."17.gif".$fin, $message);
		$message = str_ireplace("|-)",   $debut."18.gif".$fin, $message);
		$message = str_ireplace("*-)",   $debut."19.gif".$fin, $message);
		$message = str_ireplace(":-#",   $debut."20.gif".$fin, $message);
		return ($message);
	}

	//
	// Entrée : une date qui sort de MySql.
	// Sortie : une date au format "21/04/2008 à 09:37"
	//
	function date_fr($date) 
	{
		$a = substr($date, 0, 4);
		$m = substr($date, 5, 2);
		$j = substr($date, 8, 2);
		$h = substr($date, 11, 2);
		$min = substr($date, 14, 2);
		$s = substr($date, 17, 2);
		$datefr=$j.'/'.$m.'/'.$a;
		$heurefr=$h.':'.$min;
		return $datefr." à ".$heurefr;
	}
?>