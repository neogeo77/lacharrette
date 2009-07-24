<?php
	require_once "inc_connexion.php5";
	class personne
	{
		var $trigramme;
		var $nom;
		var $prenom;
		var $nbrePlaces;
		var $voiture;
		var $telTravail;
		var $telPersonnel;
		var $dateArrivee;		// date incluse
		var $dateDepart;		// date incluse
		var $email1;
		var $email2;
		var $ultraMaster;		// true si la personne a les droits d'ultra-charrette master (=gestion de toutes les charrettes)
		var $password;
		var $estAncien=false;	// true si la personne n'est actuellement plus dans la charrette
		const passwordDefaut = "password";		// C'est le mot de passe généralement attribué à la création d'un nouveau compte utilisateur.
		
		function personne($trigramme="", $nom="", $prenom="", $nbrePlaces="", $voiture="", $telTravail="", $telPersonnel="", $dateArrivee="", $dateDepart="", $email1="", $email2="", $ultraMaster="N", $password="")
		{
			$this->trigramme=strtoupper($trigramme);
			$this->nom=$nom;
			$this->prenom=$prenom;
			$this->nbrePlaces=$nbrePlaces;
			$this->telTravail=$telTravail;
			$this->telPersonnel=$telPersonnel;
			$this->dateArrivee=$dateArrivee;
			$this->dateDepart=$dateDepart;
			$this->voiture=$voiture;
			$this->email1=$email1;
			$this->email2=$email2;
			$this->ultraMaster=$ultraMaster;
			$this->password=$password;
		}
		
		function Prenom()
		{
			return ucfirst(strtolower($this->prenom));
		}
		
		function PrenomNOM()
		{
			return ucfirst(strtolower($this->prenom)). " ".strtoupper($this->nom);
		}
		
		function NOMPrenom()
		{
			return strtoupper($this->nom)." ".ucfirst(strtolower($this->prenom));
		}
		
		function enregistrer()
		{
			$sql = "UPDATE personne SET nom='".mysqlEscape($this->nom)."', prenom='".mysqlEscape($this->prenom)."', voiture='".mysqlEscape($this->voiture)."', nbrePlaces=$this->nbrePlaces, email1='".mysqlEscape(strtolower($this->email1))."', email2='".mysqlEscape(strtolower($this->email2))."', password='".mysqlEscape($this->password)."', tel_personnel='".mysqlEscape($this->telPersonnel)."', tel_travail='".mysqlEscape($this->telTravail)."', date_arrivee='$this->dateArrivee' , date_depart='$this->dateDepart',  ultramaster='$this->ultraMaster' WHERE trigramme='$this->trigramme'";
			executeSql($sql);
		}
		
		function ajouter($codeTrajet, $nbreC, $nbreP, $reportNbreC, $reportNbreP)
		{
			// Ajout dans la table personne
			$sql = "INSERT INTO personne (trigramme, nom, prenom, voiture, nbrePlaces, email1, email2, password, tel_personnel, tel_travail, date_arrivee, date_depart, ultramaster) VALUES ('$this->trigramme', '".mysqlEscape($this->nom)."', '".mysqlEscape($this->prenom)."', '".mysqlEscape($this->voiture)."', '$this->nbrePlaces', '".mysqlEscape(strtolower($this->email1))."', '".mysqlEscape(strtolower($this->email2))."', '".mysqlEscape($this->password)."', '".mysqlEscape($this->telPersonnel)."', '".mysqlEscape($this->telTravail)."', '$this->dateArrivee', '$this->dateDepart', '$this->ultramaster')";
			$ret = executeSqlErreur($sql);
			if ($ret<>"OK") return $ret;
			
			// Ajout dans la table appartenance
			$sql = "INSERT INTO appartenance (trajet, trigramme) VALUES ('$codeTrajet', '$this->trigramme')";
			$ret = executeSqlErreur($sql);
			if ($ret<>"OK") return $ret;
			
			// Ajout dans la table compteur
			$sql = "INSERT INTO compteur (trajet, trigramme, nbreC, nbreP, reportNbreC, reportNbreP) VALUES ('$codeTrajet', '$this->trigramme', $nbreC, $nbreP, $reportNbreC, $reportNbreP)";
			$ret = executeSqlErreur($sql);
			if ($ret<>"OK") return $ret;
			
			return "OK";
		}

		function relier($trigramme, $codeTrajet, $nbreC, $nbreP, $reportNbreC, $reportNbreP)
		{
			// Ajout dans la table appartenance
			$sql = "INSERT INTO appartenance (trajet, trigramme) VALUES ('$codeTrajet', '$trigramme')";
			$ret = executeSqlErreur($sql);
			if ($ret<>"OK") return $ret;
			
			// Ajout éventuel dans la table compteur
			$result = mysql_query("SELECT count(*) FROM compteur WHERE trajet='$codeTrajet' and trigramme='$trigramme'");
			$row = mysql_fetch_array($result);
			$count=$row[0];
			if ($count==0)
			{
				$sql = "INSERT INTO compteur (trajet, trigramme, nbreC, nbreP, reportNbreC, reportNbreP) VALUES ('$codeTrajet', '$trigramme', $nbreC, $nbreP, $reportNbreC, $reportNbreP)";
				$ret = executeSqlErreur($sql);
				if ($ret<>"OK") return $ret;
			}
			return "OK";
		}

		function delier($codeTrajet)
		{
			// Suppression de la table appartenance
			$sql = "DELETE FROM appartenance WHERE trajet='$codeTrajet' AND trigramme='$this->trigramme'";
			$ret = executeSqlErreur($sql);

			// Définit la date de départ, si elle ne l'est pas déjà
			if (is_null($this->dateDepart) || ($this->dateDepart=="")) $this->dateDepart=date("Y-m-d", calendrier::aujourdhui());
			$this->enregistrer();
		}

		//
		// Renvoie la liste des personnes appartenant au trajet "$codeTrajet"
		//
		function chargerTout($codeTrajet)
		{
			$result = mysql_query("SELECT P.* FROM personne P, appartenance A WHERE A.trajet='$codeTrajet' and P.trigramme=A.trigramme");
			return self::readFromQuery($result);
		}
		
		//
		// Renvoie la liste des personnes n'appartenant pas au trajet "$codeTrajet"
		//
		function chargerToutSauf($codeTrajet)
		{
			$result = mysql_query("SELECT * FROM personne WHERE trigramme NOT IN (SELECT trigramme FROM appartenance WHERE trajet='$codeTrajet') ORDER BY nom");
			return self::readFromQuery($result);
		}

		//
		// Renvoie la liste des personnes n'étant plus présente dans le trajet (=absente de la table 'appartenance') mais ayant fait partie du trajet (=présente dans la table 'compteur')
		//
		function chargerLesAnciens($codeTrajet)
		{
			$result = mysql_query("SELECT * FROM personne WHERE trigramme NOT IN (SELECT trigramme FROM appartenance WHERE trajet='$codeTrajet') AND trigramme IN (SELECT trigramme FROM compteur WHERE trajet='$codeTrajet') ORDER BY nom");
			return self::readFromQuery($result);
		}

		function readFromQuery($result)
		{
			$personnes = array();
			while($row = mysql_fetch_array($result))
			{
				$trigramme=$row['trigramme'];
				$nbrePlaces=$row['nbrePlaces'];
				$nom=$row['nom'];
				$prenom=$row['prenom'];
				$voiture=$row['voiture'];
				$telTravail=$row['tel_travail'];
				$telPersonnel=$row['tel_personnel'];
				$dateArrivee=$row['date_arrivee'];
				if ($dateArrivee=="0000-00-00") $dateArrivee="";
				$dateDepart=$row['date_depart'];
				if ($dateDepart=="0000-00-00") $dateDepart="";
				$email1=strtolower($row['email1']);
				$email2=strtolower($row['email2']);
				$email2=strtolower($row['email2']);
				$ultraMaster=$row['ultramaster'];
				$password=$row['password'];
				$personne = new personne($trigramme, $nom, $prenom, $nbrePlaces, $voiture, $telTravail, $telPersonnel, $dateArrivee, $dateDepart, $email1, $email2, $ultraMaster, $password);
				$personnes[]=$personne;
			}
			return $personnes;
		}

		function estMotDePasseParDefaut()
		{
			return ($this->password==self::passwordDefaut) || ($this->password=="systalians");		// C'est ici la SEULE information du site qui soit "propre" à la société "Systalians"
		}
		
		function estUltraMaster()
		{
			return (strtolower($this->ultraMaster)=="o");
		}
		
		function estPresenteAuJour($jour)		// $jour : chaine au format 'AAAA-MM-JJ'
		{
			$date=calendrier::toDate($jour);
			if ($this->dateDepart==null || trim($this->dateDepart)=="") $dateDepart=null; else $dateDepart=calendrier::toDate($this->dateDepart);
			if ($this->dateArrivee==null || trim($this->dateArrivee)=="") $dateArrivee=null; else $dateArrivee=calendrier::toDate($this->dateArrivee);
			
			if (($dateDepart!=null) && ($date>$dateDepart)) return false;
			if (($dateArrivee!=null) && ($date<$dateArrivee)) return false;
			return true;
		}
		
		function estPresenteLeMois($mois, $annee)		// Exemple: 5, 2009
		{
			$datePremier=mktime(0, 0, 0, $mois, 1, $annee);		// Premier jour du mois
			$dateDernier=mktime(0, 0, 0, $mois+1, 0, $annee);	// Dernier jour du mois
			
			if ($this->dateDepart==null || trim($this->dateDepart)=="") $dateDepart=null; else $dateDepart=calendrier::toDate($this->dateDepart);
			if ($this->dateArrivee==null || trim($this->dateArrivee)=="") $dateArrivee=null; else $dateArrivee=calendrier::toDate($this->dateArrivee);
			
			if (($dateDepart!=null) && ($dateDepart<$datePremier)) return false;
			if (($dateArrivee!=null) && ($dateArrivee>$dateDernier)) return false;
			return true;
		}
	}
?>