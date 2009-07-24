<?php
	require_once "inc_connexion.php5";
	class message
	{
		var $codeTrajet;
		var $texte;
		var $date;
		var $boolDefaut;	// Valeurs possible: 'O', 'N'. Indique si le message par défaut (conducteurs/passagers) doit être affiché ou pas sur la première page, au dessus du message du charrette master
		
		function message($codeTrajet, $texte, $boolDefaut, $date)
		{
			$this->codeTrajet=$codeTrajet;
			$this->texte=$texte;
			$this->boolDefaut=$boolDefaut;
			$this->date=$date;
		}

		function enregistrer()
		{
			$texte=mysqlEscape($this->texte);
			$date=date("Y-m-d", $this->date);
			$sql = "REPLACE INTO messagecm (trajet, texte, date, boolDefaut) VALUES ('$this->codeTrajet', '$texte', '$date', '$this->boolDefaut')";
			executeSql($sql);
		}

		function chargerPourDate($codeTrajet, $aDate)
		{
			$result = mysql_query("SELECT * FROM messagecm WHERE trajet='$codeTrajet' AND date='".date("Y-m-d", $aDate)."'");
			if($row = mysql_fetch_array($result))
			{
				$date=self::toDate($row['date']);
				$message = new message($codeTrajet, $row['texte'], $row['boolDefaut'], $date);
			}
			else
			{
				$message = NULL;
			}
			return $message;
		}

		function aujourdhui()
		{
			return mktime(0, 0, 0);
		}

		//
		// Transforme une chaine représentant une date au format 'AAAA-MM-JJ' (venant de MySql par exemple) en une date PHP (=un entier qui est un "unix timestamp")
		//
		function toDate($date)
		{
			$annee=intval(substr($date, 0, 4)); // AAAA
			$mois=intval(substr($date, 5, 2));  // MM
			$jour=intval(substr($date, 8, 2));  // JJ
			return mktime(0, 0, 0, $mois, $jour, $annee);
		}
	}
?>