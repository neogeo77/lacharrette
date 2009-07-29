<?php
	require_once "class.calendrier.php5";
	class html
	{
		function htmlCalendrier($calendrier, $joursDebut, $joursFin, $boolVersionTel, $boolMaster)
		{
			$aujourdhui=$calendrier->aujourdhui();
			$personnes=$calendrier->getPersonnesTriees($aujourdhui);
			
			//***************** Ligne des titres *****************
			echo '<table class="tableCalendrier">';
			echo "<tr class=\"hautTable\">";
			echo "<td>Pers.</td>";
			if (!$boolVersionTel && !$boolMaster)
			{
				echo "<td>Cond.</td>";
				echo "<td>Pass.</td>";
				echo "<td>Ratios</td>";
			}
			if ($boolMaster)
			{
				echo "<td>Pl.</td>";
			}
			
			for ($jour=$joursDebut;$jour<$joursFin;$jour++)
			{
				$dt_jour = calendrier::aujourdhuiPlus($jour);
				if ($calendrier->estWeekEndOuFerie($dt_jour)) $classe="cellWeekEnd"; else $classe = "cellSemaine";
				echo '<td nowrap class="'.$classe.'">';
				echo ucfirst(nomDuJourAbrege($dt_jour)).'<br>'.date("d", $dt_jour).'<br>'.nomDuMoisAbrege(date("n", $dt_jour));
				echo '</td>';
			}
			if ($boolMaster)
			{
				echo "<td align=center>Ratios</td>";
				echo "<td align=center>Cond.</td>";
				echo "<td align=center>Pass.</td>";
			}
			echo "</tr>";

			//***************** Lignes des personnes *****************
			foreach ($personnes as $personne)
			{
				$trigramme=$personne->trigramme;
				$prenomDistinct=$personne->prenomDistinct();
				$PrenomNom=$personne->PrenomNom();
				$nbrePlaces=$personne->nbrePlaces;
				$nbreP=$calendrier->nbrePPourDate($trigramme, $aujourdhui);
				$nbreC=$calendrier->nbreCPourDate($trigramme, $aujourdhui);
				$ratio=$calendrier->ratioPourDate($trigramme, $aujourdhui);
				$ratio=round($ratio, 5);
				if ($ratio==0) $ratio="0.0";
				$ratio=substr($ratio."00000", 0, 7);
				echo "<tr>";
				echo "<td title=\"$PrenomNom\" class=\"gaucheTable\">$prenomDistinct</td>";
				if (!$boolVersionTel && !$boolMaster)
				{
					echo "<td class=\"gaucheTable\">$nbreC</td>";
					echo "<td class=\"gaucheTable\">$nbreP</td>";
					echo "<td class=\"gaucheTable\">$ratio</td>";
				}
				if ($boolMaster)
				{
					echo "<td class=\"gaucheTable\">$nbrePlaces</td>";
				}
				for ($jour=$joursDebut;$jour<$joursFin;$jour++)
				{
					$date = calendrier::aujourdhuiPlus($jour);
					if ($calendrier->estWeekEndOuFerie($date))
					{
						echo '<td class="cellWeekEnd"></td>';
					}
					else
					{
						$code = $calendrier->getCode($date, $trigramme);
						$couleurFond = couleurJourHTML($code, $date);
						if ($date<time()) $couleurEcriture="#888888"; else $couleurEcriture="#000000";
						echo '<td style="color:'.$couleurEcriture.';" bgcolor="'.$couleurFond.'">';
						echo $code;
						echo '</td>';
					}
				}
				if ($boolMaster)
				{
					echo "<td align=center class=\"gaucheTable\">$ratio</td>";
					echo "<td align=center class=\"gaucheTable\">$nbreC</td>";
					echo "<td align=center class=\"gaucheTable\">$nbreP</td>";
				}
				echo "</tr>\n";
			}
			//***************** Ligne des "Nombre de personne par jour" *****************
			echo "<tr class=\"hautTable\">";
			if ($boolMaster)
			{
				echo "<td colspan=2>Total</td>";
			}
			elseif ($boolVersionTel)
			{
				echo "<td>Tot.</td>";
			}
			else
			{
				echo "<td colspan=4>Total charrette jour</td>";
			}
			
			for ($jour=$joursDebut;$jour<$joursFin;$jour++)
			{
				$date = calendrier::aujourdhuiPlus($jour);
				if ($calendrier->estWeekEndOuFerie($date))
				{
					echo '<td class="cellWeekEnd">';
					echo '</td>';
				}
				else
				{

					$nbre = $calendrier->nbrePersonnePourJour($date);
					echo "<td>$nbre</td>";
				}
			}
			if ($boolMaster)
			{
				echo "<td></td>";
				echo "<td></td>";
				echo "<td></td>";
			}
			echo "</table>\n";
		}


		//***************** Récapitulatif jour suivant *****************
		function htmlMessage($calendrier, $width)
		{
			// Début tableau
			echo "<table class=\"tablePresentation\" width=$width>";
			$now=getdate();
			if ($now["hours"]>=8)	// Après 8h du matin, on affiche le jour suivant
			{
				$dateRecapitulatif=$calendrier->prochainJourTravaille(1);
			}
			else
			{
				if ($calendrier->estWeekEndOuFerie($calendrier->aujourdhui()))
					$dateRecapitulatif=$calendrier->prochainJourTravaille(1);
				else
					$dateRecapitulatif=$calendrier->aujourdhui();
			}

			$message=message::chargerPourDate($calendrier->codeTrajet(), $dateRecapitulatif);
			if ($message<>NULL)
			{
				$boolDefaut=($message->boolDefaut=="O");	// indique si le message par défaut (conducteurs/passagers) doit être affiché ou pas
				$texte=$message->texte;
			}
			else
			{
				$boolDefaut=true;
				$texte="";
			}

			$nbre=$calendrier->nbrePersonnePourJour($dateRecapitulatif);
			if ($nbre>1) $pluriel="s"; else $pluriel="";
			$conducteurs=$calendrier->getConducteurs($dateRecapitulatif);
			$passagers=$calendrier->getPassagers($dateRecapitulatif);
			echo "<tr><td align=center colspan=3><b><u>".ucfirst(strtolower($calendrier->libelleSimple($dateRecapitulatif))).", $nbre personne$pluriel</u></b></td></tr>";
			echo "<tr><td colspan=3>&nbsp;</td></tr>";

			// Affichage par défaut
			if ($boolDefaut)
			{
				echo "<tr>";
				echo "<td width=20>&nbsp;</td>";
				echo "<td align=left>";
				if (sizeof($conducteurs)>1) echo "Conducteurs"; else echo "Conducteur";
				echo "</td>";
				echo "<td align=left>";
				$sep="";
				foreach ($conducteurs as $pers) {echo $sep.$pers->trigramme;$sep=", ";}
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td width=20></td>";
				echo "<td align=left>";
				if (sizeof($passagers)>1) echo "Passagers"; else echo "Passager";
				echo "</td>";
				echo "<td width=50% align=left>";
				$sep="";
				foreach ($passagers as $pers) {echo $sep.$pers->trigramme;$sep=", ";}
				echo "<tr><td colspan=3>&nbsp;</td></tr>";
				echo "</td>";
				echo "</tr>";
			}

			// Message (éventuel) du charrette master
			if (trim($texte)<>"")
			{
				$texte=stripslashes($texte);
				//$texte=cutLongWords($texte, 40, " ");
				//$texte=htmlspecialchars($texte);
				//$texte=emoticone($texte);
				$texte=nl2br($texte);
				echo "<tr>";
				echo "<td width=20>&nbsp;</td>";
				echo "<td width=430 align=left style=\"color:red;\" colspan=2>";
				echo "$texte<br><br>";
				echo "</td></tr>";
			}

			// Fin tableau
			echo "</table>";		
		
		}
	}
?>