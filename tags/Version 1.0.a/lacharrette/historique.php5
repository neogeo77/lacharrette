<?php
	require "inc_commun.php5";

	$anneesExistantes=$calendrier->anneesExistantes();
	if (isset($_GET['annee']))
	{
		$annee=intval($_GET['annee']);
	}
	else
	{
		$today = getdate();
		$annee=$today["year"];	// année actuelle
	}
	// Chargement d'un calendrier sur une année entière
	$calendrierHisto = new calendrierHistorique();
	$calendrierHisto->chargerAnnee($codeTrajet, $annee);
   	
	// En tête et menu
	require "inc_head_et_menu.php5";
	$personnes=$calendrierHisto->getPersonnesTrieesNom();
?>
<br><br><h1>HISTORIQUE</h1><br>
<?php

echo "<center>";
echo "<select onchange=\"javascript:window.location='historique.php5?annee='+this.options[this.selectedIndex].value;\">";
foreach ($anneesExistantes as $a)
{
	if ($a==$annee) $selected="selected"; else $selected="";
	echo "<option $selected value=\"$a\">$a</option>\n";
}
echo "</select>";
echo "</center>";
echo "<br><br><br>";
for ($mois=1;$mois<=12;$mois++)
{
	echo '<table class="tableCalendrier">';
	echo "<tr class=\"hautTablePetit\">";
	echo "<td></td>";
	$njours=nombreJourDansMois($mois, $annee);
	for ($jour=1;$jour<=$njours;$jour++)
	{
		$dt_jour = mktime(0, 0, 0, $mois, $jour, $annee);
		if ($calendrier->estWeekEndOuFerie($dt_jour)) $classe="cellWeekEnd"; else $classe = "cellSemaine";
		echo '<td align=center nowrap class="'.$classe.'">';
		echo ucfirst(nomDuJourAbrege($dt_jour)).'<br>'.date("d", $dt_jour).'<br>'.nomDuMoisAbrege(date("n", $dt_jour));
		echo '</td>';
	}
	echo "</tr>\n";

	foreach ($personnes as $personne)
	{
		if ($personne->estPresenteLeMois($mois, $annee))
		{
			$trigramme=$personne->trigramme;
			echo "<tr>\n";
			echo "<td class=\"gaucheTablePetit\">$trigramme</td>";
			for ($jour=1;$jour<=$njours;$jour++)
			{
				$dt_jour = mktime(0, 0, 0, $mois, $jour, $annee);
				if ($calendrier->estWeekEndOuFerie($dt_jour))
				{
					echo '<td align=center class="cellWeekEnd"></td>';
				}
				else
				{
					$code = $calendrierHisto->getCode($dt_jour, $trigramme);
					$couleur=couleurJourHTMLFutur($code);
					echo '<td align=center bgcolor="'.$couleur.'">';
					echo $code;
					echo '</td>';
				}
			}
			echo "</tr>\n";
		}
	}
	echo "</table>\n";
	echo "<br>";
}
?>
<center><a class="lienLegende" href="javascript:afficherMasquerLegende();">Afficher/masquer la légende</a></center>

<?php require "inc_legende.php5";?>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<?php
	require "inc_foot.php5";
?>