<?php
	require_once "inc_connexion.php5";
	class compteur
	{
		var $codeTrajet;
		var $trigramme;
		var $nbreC;			// nombre de fois ou la personne a conduit à la date contenue dans la table 'datecompteurs' (inclue)
		var $nbreP;			// nombre de fois ou la personne a été passager à la date contenue dans la table 'datecompteurs' (inclue)
		var $reportNbreC;	// nombre TOTAL de voyage (malgré les éventuelles modifications de compteurs)
		var $reportNbreP;	// nombre TOTAL de voyage (malgré les éventuelles modifications de compteurs)
		var $nbreCDepart;	// nombre TOTAL de voyage au moment ou la personne a quitté la charrette (si si, il faut l'enregistrer, même si cela ne parait pas évident)
		var $nbrePDepart;	// nombre TOTAL de voyage au moment ou la personne a quitté la charrette (si si, il faut l'enregistrer, même si cela ne parait pas évident)
		
		function compteur($codeTrajet, $trigramme, $nbreC, $nbreP, $reportNbreC, $reportNbreP, $nbreCDepart, $nbrePDepart)
		{
			$this->codeTrajet=$codeTrajet;
			$this->trigramme=strtoupper($trigramme);
			$this->nbreC=$nbreC;
			$this->nbreP=$nbreP;
			$this->reportNbreC=$reportNbreC;
			$this->reportNbreP=$reportNbreP;
			$this->nbreCDepart=$nbreCDepart;
			$this->nbrePDepart=$nbrePDepart;
		}
		
		function enregistrer()
		{
			$sql = "UPDATE compteur SET nbreP=$this->nbreP, nbreC=$this->nbreC, reportNbreC=$this->reportNbreC, reportNbreP=$this->reportNbreP WHERE trigramme='$this->trigramme' and trajet='$this->codeTrajet'";
			executeSql($sql);
		}
		
		function enregistrerCompteursDepart()
		{
			$sql = "UPDATE compteur SET nbreCDepart=$this->nbreCDepart, nbrePDepart=$this->nbrePDepart WHERE trigramme='$this->trigramme' and trajet='$this->codeTrajet'";
			executeSql($sql);
		}
		
		function chargerTout($codeTrajet)
		{
			$result = mysql_query("SELECT * FROM compteur WHERE trajet='".$codeTrajet."'");
			$compteurs = array();
			while($row = mysql_fetch_array($result))
			{
				$codeTrajet=$row['trajet'];
				$trigramme=$row['trigramme'];
				$nbreC=$row['nbreC'];
				$nbreP=$row['nbreP'];
				$reportNbreC=$row['reportNbreC'];
				$reportNbreP=$row['reportNbreP'];
				$nbreCDepart=$row['nbreCDepart'];
				$nbrePDepart=$row['nbrePDepart'];
				$compteur = new compteur($codeTrajet, $trigramme, $nbreC, $nbreP, $reportNbreC, $reportNbreP, $nbreCDepart, $nbrePDepart);
				$compteurs[]=$compteur;
			}
			return $compteurs;
		}
	}
?>