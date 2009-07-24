<?php
	require_once "class.calendrier.php5";
	class calendrierHistorique extends calendrier
	{
		/*********************************************************************************************************
		 * Chargement pour une année entière (fonction utilisée UNIQUEMENT pour la page Historique)
		 *********************************************************************************************************/
		function chargerAnnee($codeTrajet, $annee)
		{
			// Initialisation
			$this->calendrier = array();
			$this->joursModifies = array();
			$this->joursFeries = array();		// (c'est juste pour faire joli : la variable va être écrasée par la suite)
			$this->calendrierInitial = array();	// (c'est juste pour faire joli : la variable va être écrasée par la suite)
			$this->personnes = array();     	// (c'est juste pour faire joli : la variable va être écrasée par la suite)
			$this->compteurs = array();     	// (c'est juste pour faire joli : la variable va être écrasée par la suite)
			$this->dateCompteurs = 0;			// (c'est juste pour faire joli : la variable va être écrasée par la suite)
			
			// Chargement des données de la table 'trajet'
			$this->trajet=trajet::charger($codeTrajet);

			// Chargement de la date des compteurs
			$result = mysql_query("SELECT * FROM datecompteurs WHERE trajet='".$codeTrajet."'");
			$row = mysql_fetch_array($result);
			$this->dateCompteurs=$this->toDate($row['jour']);
			
			// Chargement des jours fériés
			$this->joursFeries=jourFerie::chargerTout($codeTrajet);

			// Chargement des personnes
			$actuels=personne::chargerTout($codeTrajet);
			$ancien=personne::chargerLesAnciens($codeTrajet);
			$this->personnes=array_merge($actuels, $ancien);

			// Chargement des compteurs des personnes
			$this->compteurs=compteur::chargerTout($codeTrajet);

			// Chargement des données de la table 'calendrier'. La table calendrierHistorique est aussi prise en compte.
			$sql = "SELECT * FROM calendrier WHERE trajet='$codeTrajet' and jour >= '$annee-01-01' and jour <= '$annee-12-31' ";
			$sql.= "UNION ";
			$sql.= "SELECT * FROM calendrierhistorique WHERE trajet='$codeTrajet' and jour >= '$annee-01-01' and jour <= '$annee-12-31'";
			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result))
			{
				$jour=$row['jour'];
				$code=$row['code'];
				$trigramme=$row['trigramme'];
				$cle=$jour."_".$trigramme;
				if ($code<>"P") $this->calendrier[$cle]=$code;
			}
		}
		
		/*********************************************************************************************************
		 * getCode est LA fonction principale permettant de lire des données dans le calendrier
		 * Elle est surchargée ici pour prendre en compte la date de départ des personnes : si la personne n'est plus présente à la date $jour, la fonction renvoie "-"
		 *********************************************************************************************************/
		function getCode($jour, $trigramme)		// $jour peut être un timestamp ou une chaine au format 'AAAA-MM-JJ'
		{
			if (is_int($jour)) $jour=date("Y-m-d", $jour);
			if ($this->estWeekEndOuFerie($jour)) return "";
			$personne=$this->getPersonne($trigramme);
			if (!$personne->estPresenteAuJour($jour)) return "-";
			$cle=$jour."_".$trigramme;
			if (isset($this->calendrier[$cle])) return $this->calendrier[$cle];
			return "P";
		}
	}
?>