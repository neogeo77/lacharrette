<?php
	require_once "inc_connexion.php5";
	class trajet
	{
		var $codeTrajet;
		var $titre;
		var $texteMsgDefaut;
		var $trajetLie;
		var $couleur;
		var $lienGoogleMap;
		var $lienAncienneCharrette;
		var $distance;
		var $ordre;
		
		function trajet($codeTrajet, $titre, $texteMsgDefaut, $lienGoogleMap, $lienAncienneCharrette, $trajetLie, $couleur, $distance, $ordre)
		{
			$this->codeTrajet=$codeTrajet;
			$this->titre=$titre;
			$this->texteMsgDefaut=$texteMsgDefaut;
			$this->lienGoogleMap=$lienGoogleMap;
			$this->lienAncienneCharrette=$lienAncienneCharrette;
			$this->trajetLie=$trajetLie;
			$this->couleur=$couleur;
			$this->distance=$distance;
			$this->ordre=$ordre;
		}

		function charger($codeTrajet)
		{
			$result = mysql_query("SELECT * FROM trajet WHERE trajet='$codeTrajet'");
			$row = mysql_fetch_array($result);
			$codeTrajet=$row['trajet'];
			$titre=$row['titre'];
			$texteMsgDefaut=$row['texteMsgDefaut'];
			$trajetLie=$row['trajetLie'];
			$couleur=$row['couleur'];
			$lienGoogleMap=$row['lienGoogleMap'];
			$lienAncienneCharrette=$row['lienAncienneCharrette'];
			$ordre=$row['ordre'];
			$distance=$row['distance'];
			$trajet = new trajet($codeTrajet, $titre, $texteMsgDefaut, $lienGoogleMap, $lienAncienneCharrette, $trajetLie, $couleur, $distance, $ordre);
			return $trajet;
		}

		function chargerTout()
		{
			$result = mysql_query("SELECT * FROM trajet ORDER BY ordre");
			$trajets = array();
			while($row = mysql_fetch_array($result))
			{
				$codeTrajet=$row['trajet'];
				$titre=$row['titre'];
				$texteMsgDefaut=$row['texteMsgDefaut'];
				$lienGoogleMap=$row['lienGoogleMap'];
				$trajetLie=$row['trajetLie'];
				$couleur=$row['couleur'];
				$lienAncienneCharrette=$row['lienAncienneCharrette'];
				$ordre=$row['ordre'];
				$distance=$row['distance'];
				$trajet = new trajet($codeTrajet, $titre, $texteMsgDefaut, $lienGoogleMap, $lienAncienneCharrette, $trajetLie, $couleur, $distance, $ordre);
				$trajets[]=$trajet;
			}
			return $trajets;
		}
	}
?>