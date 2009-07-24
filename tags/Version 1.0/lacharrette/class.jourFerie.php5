<?php
	require_once "inc_connexion.php5";
	class jourFerie
	{
		function chargerTout($codeTrajet)
		{
			$joursFeries = array();
			$result = mysql_query("SELECT * FROM joursferies WHERE trajet='".$codeTrajet."' ORDER by jour DESC");
			while($row = mysql_fetch_array($result))
			{
				$jour=$row['jour'];
				$joursFeries[]=$jour;
			}
			return $joursFeries;
		}

		function ajouter($jour, $codeTrajet)
		{
			// Ajout dans la table personne
			$sql = "INSERT INTO joursferies (jour, trajet) VALUES ('$jour', '$codeTrajet')";
			return executeSqlErreur($sql);
		}

		function supprimer($jour, $codeTrajet)
		{
			// Ajout dans la table personne
			$sql = "DELETE FROM joursferies WHERE jour='$jour' AND trajet='$codeTrajet'";
			return executeSqlErreur($sql);
		}
	}
?>