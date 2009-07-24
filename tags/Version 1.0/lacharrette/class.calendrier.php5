<?php
	require_once "class.personne.php5";
	require_once "class.compteur.php5";
	require_once "class.trajet.php5";
	require_once "class.jourFerie.php5";
	require_once "inc_connexion.php5";
	class calendrier
	{
		var $calendrier;			// Contient l'ensemble du calendrier (voir un exemple du contenu de cette variable à la fin de ce fichier)
		var $calendrierInitial;		// Copie de "calendrier" au moment de la lecture en base. Il permet de connaitre ce qui a été modifié par l'utilisateur
		var $joursModifies;         // Contient les jours modifiés par l'utilisateur. Cela permet de parcourir uniquement ces jours au moment de l'enregistrement.
		var $joursFeries;			// Contient les jours fériés (table 'joursferies')
		var $personnes;				// Contient les personnes (table 'personne')
		var $trajet;				// Contient le trajet (table 'trajet')
		var $compteurs;				// Contient les compteurs (table 'compteur')
		var $dateCompteurs;			// valeur contenue dans l'unique ligne de la table datecompteurs. Correspond à la date des compteurs nbreP et nbreC de la table 'personne'. Stockée sous forme de timestamp
		const deltaCompteurs = -9;	// -9 signifie : les compteurs doivent être enregistrés à la date actuelle moins 9 jours. Cela signifie aussi que tout ce qui existe 9 jours avant la date d'aujourd'hui est effacé dans la table "calendrier" au moment du "enregistrerConducteurs()"
		
		function calendrier()
		{
		}

		function codeTrajet()	// trigramme du trajet (exemple: "ORL")
		{
			return $this->trajet->codeTrajet;
		}

		function dateCompteurs()
		{
			return $this->dateCompteurs;
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

		function estWeekEndOuFerie($date)	// $date peut être un timestamp ou une chaine au format 'AAAA-MM-JJ'
		{
			return $this->estWeekEnd($date) || $this->estFerie($date);
		
		}
		
		function estTravaille($date)
		{
			return !$this->estWeekEndOuFerie($date);
		}
		
		function estFerie($date) // $date peut être un timestamp ou une chaine au format 'AAAA-MM-JJ'
		{
			if (is_int($date)) $date=date("Y-m-d", $date);
			return in_array($date, $this->joursFeries);
		}

		function estWeekEnd($date)	// $date peut être un timestamp ou une chaine au format 'AAAA-MM-JJ'
		{
			if (is_string($date)) $date=$this->toDate($date);
			$gd = getdate($date);
			$jourSemaine = $gd['wday'];
			if ($jourSemaine==0 or $jourSemaine==6)
				$ret=true;
			else
				$ret=false;
			return $ret;
		}
		
		//
		// Renvoie une date (de type timestamp) corresondant à la date du jour + $nbreJours jours.
		//
		function aujourdhuiPlus($nbreJours)
		{
			$today = getdate();
			$jourNow=$today["mday"];
			$moisNow=$today["mon"];
			$anneeNow=$today["year"];
			return mktime(0, 0, 0, $moisNow, $jourNow + $nbreJours, $anneeNow);
		}

		//
		// Renvoie une date (de type timestamp) corresondant à $date + $nbreJours jours.
		//
		function ajouterJours($date, $nbreJours)
		{
			$today = getdate($date);
			$jourNow=$today["mday"];
			$moisNow=$today["mon"];
			$anneeNow=$today["year"];
			return mktime(0, 0, 0, $moisNow, $jourNow + $nbreJours, $anneeNow);
		}

		function differenceDeJour($date1, $date2)
		{
			return round(($date2-$date1)/(3600*24));
		}
		
		function aujourdhui()
		{
			return mktime(0, 0, 0);
		}
		
		/*********************************************************************************************************
		 * Enregistre le calendrier
		 *********************************************************************************************************
		 *
		 * L'enregistrement se fait de la manière suivante :
		 *
		 *    1 - REPLACE : seuls les codes modifiés sont enregistrés
		 *    2 - DELETE : Suppression des "P". Remarque: les seuls 'P' qui vont être supprimés sont ceux qui auront 
		 *        été insérés par le REPLACE précédent. Et ces derniers sont ceux qui viennent d'être "rétablit" 
		 *        par l'utilisateur (ils sont donc TRES peu nombreux, voire inexistants dans 98% des cas)
		 *    3 - Détermine la nouvelle date des compteurs (=jour actuel - 9 jours (cf deltacompteur))
		 *    4 - Si cette nouvelle date des compteurs n'est pas celle qui existe en base (table datecompteurs) :
		 *  	      - modifie les compteurs nbreC et nbreP dans la table 'personne' (qui doivent être ceux à la date $nouvelleDateCompteurs)
		 *            - enregistre la nouvelle date des compteurs dans la table 'datecompteurs'
		 *    5 - Recharge TOUT
		 *
		 * Remarque: chez Free, les bases de type innodb ne sont pas disponible -> impossible de faire du LOCK TABLE.
		 * On doit se contenter d'effectuer une transaction simple (START TRANSACTION -> COMMIT), sans verrouillage des tables.
		 *
		 ********************************************************************************************************/
		function enregistrer()
		{
			// Commence la transaction
			executeSql("START TRANSACTION");
			//echo "<pre>";print_r($this->joursModifies);echo "</pre>";
			
			// Replaces
			foreach ($this->joursModifies as $cle)		// exemple de $cle : '2008-12-29_PPI'
			{
				$jour=substr($cle, 0, 10);
				$trigramme=substr($cle, 11, 3);
				if ($this->estCodeModifie($jour, $trigramme))
				{
					$code=$this->getCode($jour, $trigramme);
					if ($code<>"P")
					{
						$sql = "REPLACE INTO calendrier (trajet, jour, trigramme, code) VALUES ('".$this->codeTrajet()."', '$jour', '$trigramme', '$code')";
					}
					else
					{
						$sql = "DELETE FROM calendrier WHERE trajet='".$this->codeTrajet()."' and jour='".$jour."' and trigramme='".$trigramme."'";
					}
					executeSql($sql);
				}
			}

			// Calcule de la nouvelle date des compteurs
			$nouvelleDateCompteurs=$this->aujourdhuiPlus(self::deltaCompteurs);

			if ($nouvelleDateCompteurs<>$this->dateCompteurs)
			{
				// Modifie les compteurs nbreC et nbreP dans la table 'compteur' (qui doivent être ceux à la date $nouvelleDateCompteurs)
				foreach ($this->compteurs as $compteur)
				{
					$trigramme=$compteur->trigramme;
					$compteur->nbreP = $this->nbrePPourDate($trigramme, $nouvelleDateCompteurs);
					$compteur->nbreC = $this->nbreCPourDate($trigramme, $nouvelleDateCompteurs);
					$compteur->enregistrer();	// sauvegarde en base
				}

				// Enregistre la nouvelle date des compteurs dans la table 'datecompteurs'
				$this->modifierDateCompteurs($nouvelleDateCompteurs);
			}
			
			// Commit
			executeSql("COMMIT");
			
			// Recharge TOUT
			$this->recharger();
		}
		
		/*********************************************************************************************************
		 * Chargement
		 *********************************************************************************************************/
		function charger($codeTrajet)
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
			$this->personnes=personne::chargerTout($codeTrajet);

			// Chargement des compteurs des personnes
			$this->compteurs=compteur::chargerTout($codeTrajet);

			// Chargement des données de la table 'calendrier'
			$result = mysql_query("SELECT * FROM calendrier WHERE trajet='".$codeTrajet."' and jour >= '".date("Y-m-d", $this->dateCompteurs)."'");
			while($row = mysql_fetch_array($result))
			{
				$jour=$row['jour'];
				$code=$row['code'];
				$trigramme=$row['trigramme'];
				$cle=$jour."_".$trigramme;
				if ($code<>"P") $this->calendrier[$cle]=$code;
			}

			// Les données qui viennent d'être chargées sont copiées (l'opérateur "=" effectue une copie des valeurs) dans $calendrierInitial
			$this->calendrierInitial=$this->calendrier;
		}
		
		
		function recharger()
		{
			$this->charger($this->codeTrajet());
		}

		/*********************************************************************************************************
		 * getCode est LA fonction principale permettant de lire des données dans le calendrier
		 *********************************************************************************************************/
		function getCode($jour, $trigramme)		// $jour peut être un timestamp ou une chaine au format 'AAAA-MM-JJ'
		{
			if (is_int($jour)) $jour=date("Y-m-d", $jour);
			if ($this->estWeekEndOuFerie($jour)) return "";
			$cle=$jour."_".$trigramme;
			if (isset($this->calendrier[$cle])) return $this->calendrier[$cle];
			return "P";
		}

		//
		// Comme getCode(), mais les données sont lues dans $calendrierInitial
		//
		function getCodeInitial($jour, $trigramme)		// $jour peut être un timestamp ou une chaine au format 'AAAA-MM-JJ'
		{
			if (is_int($jour)) $jour=date("Y-m-d", $jour);
			if ($this->estWeekEndOuFerie($jour)) return "";
			$cle=$jour."_".$trigramme;
			if (isset($this->calendrierInitial[$cle])) return $this->calendrierInitial[$cle];
			return "P";
		}

		//
		// Renvoie true si le code a été modifié par l'utilisateur pour le jour et le trigramme donné
		//
		function estCodeModifie($jour, $trigramme)
		{
			return ($this->getCode($jour, $trigramme) <> $this->getCodeInitial($jour, $trigramme));
		}

		/*********************************************************************************************************
		 * setCode est LA fonction principale permettant modifier les données du calendrier
		 *********************************************************************************************************/
		function setCode($jour, $trigramme, $code)
		{
			if ($code=="") return;	// ne rien changer pour ce jour
			if (is_int($jour)) $jour=date("Y-m-d", $jour);
			$cle=$jour."_".$trigramme;
			if ($code=="P")
				unset($this->calendrier[$cle]);		// Les 'P' ne sont pas stockés
			else
				$this->calendrier[$cle]=$code;
			if ($this->estCodeModifie($jour, $trigramme))
			{
				$this->joursModifies[]=$cle;
			}
		}

		// Renvoi le nbreC à la date $date, pour la personne $trigramme
		function nbreCPourDate($trigramme, $dateNbre)
		{
			$compteur=$this->getCompteur($trigramme);
			$nbreC=$compteur->nbreC;
			$diff=$this->differenceDeJour($this->dateCompteurs, $dateNbre);
			for ($j=1;$j<=$diff;$j++)
			{
				$date=$this->ajouterJours($this->dateCompteurs, $j);
				if ($this->estConducteur($date, $trigramme)) $nbreC=$nbreC+1;
			}
			return $nbreC;
		}
		
		// Renvoie le nbreP à la date $date, pour la personne $trigramme
		function nbrePPourDate($trigramme, $dateNbre)
		{
			$compteur=$this->getCompteur($trigramme);
			$nbreP=$compteur->nbreP;
			$diff=$this->differenceDeJour($this->dateCompteurs, $dateNbre);
			for ($j=1;$j<=$diff;$j++)
			{
				$date=$this->ajouterJours($this->dateCompteurs, $j);
				if ($this->estPassager($date, $trigramme)) $nbreP=$nbreP+1;
			}
			return $nbreP;
		}

		// Renvoie le nombre de jours ouvrés entre dateCompteurs et aujourd'hui
		function nbreJoursOuvresDepuisDateCompteur()
		{
			$nbre=0;
			for ($j=1;$j<=-self::deltaCompteurs;$j++)
			{
				$date=$this->ajouterJours($this->dateCompteurs, $j);
				if ($this->estTravaille($date)) $nbre++;
			}
			return $nbre;
		}

		function ratioPourDate($trigramme, $dateNbre)
		{
			if ($this->nbrePPourDate($trigramme, $dateNbre)==0) return 0;
			return $this->nbreCPourDate($trigramme, $dateNbre) / $this->nbrePPourDate($trigramme, $dateNbre);
		}
		
		function modifierDateCompteurs($date)
		{
			$sql = "UPDATE datecompteurs SET jour='".date("Y-m-d", $date)."' WHERE trajet='".$this->codeTrajet()."'";		// la table datecompteurs ne contient qu'une seule ligne par trajet
			executeSql($sql);
		}


		// Mise à jour des compteurs de départ (cf. nbreCDepart et nbrePDepart de class.compteur.php5)
		function modifierCompteursDeDepart($trigramme)
		{
			$aujourdhui=$this->aujourdhui();
			$nbreP=$this->nbrePPourDate($trigramme, $aujourdhui);
			$nbreC=$this->nbreCPourDate($trigramme, $aujourdhui);
			$compteur=$this->getCompteur($trigramme);
			$reportNbreC=$compteur->reportNbreC;
			$reportNbreP=$compteur->reportNbreP;
			$compteur->nbreCDepart = $reportNbreC+$nbreC;
			$compteur->nbrePDepart = $reportNbreP+$nbreP;
			$compteur->enregistrerCompteursDepart();
		}

		function getPersonnes()
		{
			return $this->personnes;
		}

		//
		// Renvoie la liste de toutes les personnes, triée selon les ratios croissants à la date $date.
		//
		function getPersonnesTriees($date)
		{
			$persTriees=array();
			$ratio=array();
			foreach($this->personnes as $personne)
			{
				$ratio[]=$this->ratioPourDate($personne->trigramme, $date);
				$persTriees[]=$personne;
			}
			array_multisort($ratio, SORT_NUMERIC, $persTriees);
			return $persTriees;
		}

		
		//
		// Renvoie la liste de toutes les personnes, triée selon les ratios croissants à la date $date.
		// Différence avec la fonction précédente : si le jour travaillé suivant (càd le jour pour lequel le tri est effectué), un conducteur est en "I" (voiture indisponible) alors celui-ci sera en bas de tableau (pour faciliter le travail du charrette master)
		//
		function getPersonnesTrieesPourMaster($date)
		{
			$persTriees=array();
			$ratio=array();
			$jourTravailleSuivant=$this->jourTravailleSuivant($date);
			foreach($this->personnes as $personne)
			{
				$codeSuivant=$this->getCode($jourTravailleSuivant, $personne->trigramme);
				if ($codeSuivant<>"I")
				{
					$ratio[]=$this->ratioPourDate($personne->trigramme, $date);
				}
				else
				{
					$ratio[]=1;	// Voir différence ci-dessus
				}
				$persTriees[]=$personne;
			}
			array_multisort($ratio, SORT_NUMERIC, $persTriees);
			return $persTriees;
		}

		//
		// Renvoie la liste de toutes les personnes, triée selon leur date d'arrivée
		//
		function getPersonnesTrieesDateArrivee()
		{
			$persTriees=array();
			$dates=array();
			foreach($this->personnes as $personne)
			{
				$dates[]=$personne->dateArrivee;
				$persTriees[]=$personne;
			}
			array_multisort($dates, SORT_STRING, $persTriees);
			return $persTriees;
		}

		//
		// Renvoie la liste de toutes les personnes, triée selon leur nom
		//
		function getPersonnesTrieesNom()
		{
			$persTriees=array();
			$noms=array();
			foreach($this->personnes as $personne)
			{
				$noms[]=$personne->nom;
				$persTriees[]=$personne;
			}
			array_multisort($noms, SORT_STRING, $persTriees);
			return $persTriees;
		}
		
		//
		// Renvoie la liste de toutes les anciennes personnes de la charrette, triée selon leur nom
		//
		function getAnciennesPersonnesTrieesNom()
		{
			$persTriees=array();
			$noms=array();
			$anciens=personne::chargerLesAnciens($this->codeTrajet());
			foreach($anciens as $personne)
			{
				$noms[]=$personne->nom;
				$persTriees[]=$personne;
			}
			array_multisort($noms, SORT_STRING, $persTriees);
			return $persTriees;
		}
		
		function getPersonne($trigramme)
		{
			// On commence par rechercher dans le tableau $this->personnes
			$ret=null;
			$personnes=$this->personnes;
			foreach($personnes as $personne)
			{
				if ($personne->trigramme==$trigramme) $ret=$personne;
			}
			
			// Si la personne n'a pas été trouvée dans le tableau, on effectue une recherche parmis les "anciens" de la charrette
			if ($ret==null)
			{
				$personnes=personne::chargerLesAnciens($this->codeTrajet());
				foreach($personnes as $personne)
				{
					if ($personne->trigramme==$trigramme)
					{
						$ret=$personne;
						$ret->estAncien=true;
					}
				}
			}
			return $ret;
		}
		
		function getCompteur($trigramme)
		{
			foreach($this->compteurs as $compteur)
			{
				if ($compteur->trigramme==$trigramme)
				{
					$ret=$compteur;
				}
			}
			return $ret;
		}
		
		function estConducteur($jour, $trigramme)	// $jour doit être un timestamp
		{
			$code=$this->getCode($jour, $trigramme);
			return (($code=="C") || ($code=="V"));
		}
		
		function estPassager($jour, $trigramme)		// $jour doit être un timestamp
		{
			$code=$this->getCode($jour, $trigramme);
			return (($code=="P") || ($code=="I"));
		}
		
		function estAbsentConvenant($jour, $trigramme)		// $jour doit être un timestamp
		{
			$code=$this->getCode($jour, $trigramme);
			return (($code=="A") || ($code=="O"));
		}
		//
		// Retourne la liste des conducteurs pour le jour $jour
		//
		function getConducteurs($jour)
		{
			$conducteurs=array();
			foreach ($this->personnes as $personne)
			{
				$trigramme=$personne->trigramme;
				if ($this->estConducteur($jour, $trigramme)) $conducteurs[]=$personne;
			}			
			return $conducteurs;
		}

		//
		// Retourne la liste des passagers pour le jour $jour
		//
		function getPassagers($jour)
		{
			$passagers=array();
			foreach ($this->personnes as $personne)
			{
				$trigramme=$personne->trigramme;
				if ($this->estPassager($jour, $trigramme)) $passagers[]=$personne;
			}			
			return $passagers;
		}

		//
		// Retourne la liste des personnes absentes ou en convenance pour le jour $jour
		//
		function getAbsentsConvenants($jour)
		{
			$passagers=array();
			foreach ($this->personnes as $personne)
			{
				$trigramme=$personne->trigramme;
				if ($this->estAbsentConvenant($jour, $trigramme)) $passagers[]=$personne;
			}			
			return $passagers;
		}

		//
		// Renvoie une date : le nième prochain jour travaillé, en prenant en compte les week-end et les jours fériés
		// Exemple, on est mercredi:
		//		- prochainJourTravaille(0) -> mercredi
		//		- prochainJourTravaille(1) -> jeudi
		//		- prochainJourTravaille(2) -> vendredi
		//		- prochainJourTravaille(3) -> lundi
		//
		// Exemple, on est samedi:
		//		- prochainJourTravaille(0) -> samedi	// Si si, samedi, même si ce n'est pas un jour travaillé. C'est une convention qui nous arrange ici (et qui est presque logique)
		//		- prochainJourTravaille(1) -> lundi
		//		- prochainJourTravaille(2) -> mardi
		//
		function prochainJourTravaille($nJour)
		{
			if ($nJour==0) return $this->aujourdhui();
			if ($this->estWeekEndOuFerie($this->aujourdhui())) $nJour=$nJour-1;
			$j=0;
			$jTravaille=0;
			while(true)
			{
				$jour = $this->aujourdhuiPlus($j);
				if ($this->estTravaille($jour))
				{
					if ($jTravaille==$nJour) break; else $jTravaille++;
				}
				$j++;
			}
			return $jour;
		}

		//
		// Renvoie le jour travaillé suivant
		//
		function jourTravailleSuivant($date)
		{
			$j=1;
			while(true)
			{
				$jour=$this->ajouterJours($date, $j);
				if ($this->estTravaille($jour)) break;
				$j++;
			}
			return $jour;
		}
	
		//
		// Renvoie le nom du jour (exemple: lundi 14) ou "Demain" ou "Après demain" le cas échéant
		//
		function libelleSimple($jour)
		{
			if ($jour==$this->aujourdhuiPlus(0)) return "Aujourd'hui";
			if ($jour==$this->aujourdhuiPlus(1)) return "Demain";
			return nomDuJour($jour)." ".date("j", $jour);
		}

		//
		// Renvoie le nom du jour (exemple: lundi 14)
		//
		function libelleJour($jour)
		{
			return nomDuJour($jour)." ".date("j", $jour);
		}

		//
		// Comme libelleSimple(), mais ajoute "d'" ou "de" devant le nom du jour
		//
		function deLibelleSimple($jour)
		{
			$libelle=$this->libelleSimple($jour);
			if (strtoupper(substr($libelle,0,1))=="A")
			{
				$libelle="d'".$libelle;
			}
			else
			{
				$libelle="de ".$libelle;
			}
			return $libelle;
		}
		
		function nbrePersonnePourJour($date)
		{
			$nbre=0;
			foreach($this->personnes as $personne)
			{
				$trigramme=$personne->trigramme;
				if ($this->estPassager($date, $trigramme) || $this->estConducteur($date, $trigramme))
				{
					$nbre=$nbre+1;
				}
			}
			return $nbre;
		}

		function nbreVoituresPourJour($date)
		{
			$nbre=0;
			foreach($this->personnes as $personne)
			{
				$trigramme=$personne->trigramme;
				if ($this->estConducteur($date, $trigramme))
				{
					$nbre=$nbre+1;
				}
			}
			return $nbre;
		}

		// Moyenne des nbreP des personnes de la charrette
		function moyenneNbreP()
		{
			$tot=0;
			$n=0;
			foreach($this->personnes as $personne)
			{
				$nbreP=$this->nbrePPourDate($personne->trigramme, $this->aujourdhui());
				$tot=$tot+$nbreP;
				$n++;
			}
			if ($n<>0) $moy=$tot/$n; else $moy=0;
			return round($moy);
		}

		// Moyenne des nbreC des personnes de la charrette
		function moyenneNbreC()
		{
			$tot=0;
			$n=0;
			foreach($this->personnes as $personne)
			{
				$nbreC=$this->nbreCPourDate($personne->trigramme, $this->aujourdhui());
				$tot=$tot+$nbreC;
				$n++;
			}
			if ($n<>0) $moy=$tot/$n; else $moy=0;
			return round($moy);
		}

		//
		// Renvoie l'ensemble des années présentes dans la table calendrier
		// Uniquement utilisé pour la page Historique.
		//
		function anneesExistantes()
		{
			$annees = array();
			$result = mysql_query("SELECT distinct year(jour) FROM calendrier WHERE trajet='".$this->codeTrajet()."' ORDER BY 1");
			while($row = mysql_fetch_array($result))
			{
				$annee=$row[0];
				$annees[]=$annee;
			}
			return $annees;
		}
	}

/* Exemple de $calendrier

Array
(
    [2008-12-29_PPI] => C
    [2008-12-30_YDE] => A
    [2008-12-30_AOC] => A
    [2008-12-30_GGO] => A
    [2008-12-22_MBE] => A
    [2008-12-29_AOC] => I
    [2008-12-26_LJU] => V
    [2008-12-26_GGO] => A
    [2008-12-25_PPI] => V
    [2008-12-24_LJU] => A
    [2008-12-23_BME] => A
    [2008-12-23_DFO] => A
    [2008-12-29_BME] => C
    [2008-12-30_DFO] => V
    [2008-12-29_LJU] => C
    [2008-12-19_BME] => V
    [2008-12-22_LJU] => C
    [2008-12-19_MBE] => A
)
*/
?>