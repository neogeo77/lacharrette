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
?>

<!--[if gte IE 5.5000]> <script type=text/javascript src="pngfix.js"></script><![endif]-->

<br><br><h1>STATISTIQUES</h1><br>
<br><br>
<center>
<hr width=90%>
<span class="titreInformation">Nombre de personnes par voiture</span>
<hr width=90%>
<span style="font-size:11px;">(Cf. page <a style="font-size:11px;" target="_blank" href="historique.php5">historique</a>)<br><br></span>
<?php
echo "<select onchange=\"javascript:window.location='statistiques.php5?annee='+this.options[this.selectedIndex].value;\">";
foreach ($anneesExistantes as $a)
{
	if ($a==$annee) $selected="selected"; else $selected="";
	echo "<option $selected value=\"$a\">$a</option>\n";
}
echo "</select>";
echo "<br><br>";
$moisRemplissage=array();
$nbreTotalAnneePers=0;
$nbreTotalAnneeVoit=0;
for ($mois=1;$mois<=12;$mois++)
{
	$nbreTotalPers=0;
	$nbreTotalVoit=0;
	$njours=nombreJourDansMois($mois, $annee);
	for ($jour=1;$jour<=$njours;$jour++)
	{
		$dt_jour = mktime(0, 0, 0, $mois, $jour, $annee);
		if (!$calendrier->estWeekEndOuFerie($dt_jour))
		{
			$nbrePers = $calendrierHisto->nbrePersonnePourJour($dt_jour);
			$nbreVoit = $calendrierHisto->nbreVoituresPourJour($dt_jour);
			if ($nbreVoit<>0 && $dt_jour<=calendrier::aujourdhui())
			{
				$nbreTotalPers+=$nbrePers;
				$nbreTotalVoit+=$nbreVoit;
				$nbreTotalAnneePers+=$nbrePers;
				$nbreTotalAnneeVoit+=$nbreVoit;
			}			
		}
	}

	$cle=nomDuMoisAbrege(date("n", $dt_jour));
	if ($nbreTotalVoit<>0)
	{
		$moisRemplissage[$cle]=$nbreTotalPers/$nbreTotalVoit;
		// echo "Mois $cle : $nbreTotalPers / $nbreTotalVoit<br>";
	}
	else
	{
		$moisRemplissage[$cle]=0;
	}
}
// echo "<pre>";print_r($moisRemplissage);

$data="";
$libellesMois="";
$libellesData="";
$sepData="";
foreach ($moisRemplissage as $mois=>$taux)
{
	$data.=$sepData.round(($taux)*100/6, 2);	// Avec Google Chart API, l'échelle est fixe : 0-100
	$libellesMois.="|".$mois;
	$libellesData.=$sepData.round($taux, 2);
	$sepData=",";
}

$libellesMois=utf8_encode($libellesMois);
echo "<img src=\"http://chart.apis.google.com/chart?cht=bvs&chd=t:$data|$libellesData&chs=500x200&chxl=0:$libellesMois&chxt=x,y&chxr=1,0,6&chm=N*f*,000000,1,-1,10&chma=30,30,30,30&chbh=25,15&chf=bg,s,FFFFFF00&chco=FFCC33,FFFFFF00\">";
?>

<br><br><br><br>
<hr width=90%>
<span class="titreInformation">Total des trajets et distances par personne (sans les convenances)</span>
<hr width=90%>
<?php
    // Constitution des tableaux, et tri des personnes par nombre de trajets
    $personnesVrac=$calendrier->getPersonnes();
	$personnes=array();
	$nbreTrajets=array();
	$nbreConds=array();
	$nbrePasss=array();
	$dateArrivees=array();
	$aujourdhui=$calendrier->aujourdhui();
	foreach($personnesVrac as $personne)
	{
		$trigramme=$personne->trigramme;
		$compteur=$calendrier->getCompteur($trigramme);
		$nbreP=$calendrier->nbrePPourDate($trigramme, $aujourdhui);
		$nbreC=$calendrier->nbreCPourDate($trigramme, $aujourdhui);
		$reportNbreC=$compteur->reportNbreC;
		$reportNbreP=$compteur->reportNbreP;
		$nbreTrajet=$reportNbreC+$reportNbreP+$nbreP+$nbreC;
		$dateArrivee=$personne->dateArrivee;
		$dateArrivee=calendrier::toDate($dateArrivee);
		$dateArrivee=date("d-m-Y", $dateArrivee);
		$personnes[]=$personne;
		$dateArrivees[$trigramme]=$dateArrivee;
		$nbreTrajets[$trigramme]=$nbreTrajet;
		$nbrePasss[$trigramme]=$nbreP+$reportNbreP;
		$nbreConds[$trigramme]=$nbreC+$reportNbreC;
	}
	array_multisort($nbreTrajets, SORT_NUMERIC, SORT_DESC, $personnes);
	
	// Affichage
	$distance=$calendrier->trajet->distance;
	if (!isset($distance)) $distance=0;
	if($distance==0) echo "<br><i>Attention : la longueur du trajet n'a pas été définie ! Merci de la communiquer à l'administrateur.</i><br><br>";
	echo "<span style=\"font-size:11px;\">Longueur du trajet : 2*".$distance."km<br></span><br>";

	// Ligne des titres
	echo '<table align=center class="tableCalendrier">';
	echo "<tr class=\"hautTableAutreCouleur\">";
	echo "<td>Pers.</td>";
	echo "<td>Date arrivée</td>";
	echo "<td>Nbre cond.</td>";
	echo "<td>Nbre pass.</td>";
	echo "<td>Nbre total</td>";
	echo "<td>Distance (km)</td>";
	echo "<td>Nbre tours<br>de Terre</td>";
	echo "</tr>";

	// Lignes des personnes
	for($i=0; $i<sizeof($personnes); $i++)
	{
		$personne=$personnes[$i];
		$trigramme=$personne->trigramme;
		$nbreTrajet=$nbreTrajets[$trigramme];
		echo "<tr>";
		echo "<td class=\"gaucheTable\" style=\"text-align:left;\">".$personne->PrenomNOM()."</td>";
		echo "<td class=\"gaucheTable\">&nbsp;".$dateArrivees[$trigramme]."&nbsp;</td>";
		echo "<td align=center class=\"gaucheTable\">".$nbreConds[$trigramme]."</td>";
		echo "<td align=center class=\"gaucheTable\">".$nbrePasss[$trigramme]."</td>";
		echo "<td align=center class=\"gaucheTable\">".$nbreTrajet."</td>";
		echo "<td style=\"text-align:right;\" class=\"gaucheTable\">".number_format($nbreTrajet*2*$distance, 0, '', ' ')."&nbsp;&nbsp;&nbsp;</td>";
		echo "<td style=\"text-align:right;\" class=\"gaucheTable\">".number_format($nbreTrajet*2*$distance/40000, 1, ',', ' ')."&nbsp;&nbsp;&nbsp;</td>";
		echo "</tr>\n";
	}
	echo "</table>\n";
	echo "<br><br>\n";
?>
<br><br>
<hr width=90%>
<span class="titreInformation">Bilan CO<sub>2</sub> pour l'année <?php echo $annee;?></span>
<hr width=90%>
<?php
	echo "<span style=\"font-size:11px;\">Calcul pour un trajet de 2*".$distance."km, et un rejet de CO<sub>2</sub> de 136g/km (XSARA PICASSO 1.6HDi)<br></span><br>";
	echo '<table align=center class="tableCalendrier">';
	echo "<tr class=\"hautTableAutreCouleur\">";
	echo "<td></td>";
	echo "<td>Calcul</td>";
	echo "<td>Résultat</td>";
	echo "</tr>";
	echo "<tr class=\"gaucheTable\">";
	echo "<td>Rejet avec co-voiturage</td><td>&nbsp;&nbsp;$nbreTotalAnneeVoit trajets * 2 * ".$distance."km * 136g/km&nbsp;&nbsp;</td><td style=\"text-align:right;\">&nbsp;".number_format($nbreTotalAnneeVoit*2*$distance*0.136, 0, '', ' ')." kg&nbsp;&nbsp;&nbsp;</td>";
	echo "</tr>";
	echo "<tr class=\"gaucheTable\">";
	echo "<td>Rejet sans co-voiturage</td><td>&nbsp;&nbsp;$nbreTotalAnneePers trajets * 2 * ".$distance."km * 136g/km&nbsp;&nbsp;</td><td style=\"text-align:right;\">&nbsp;".number_format($nbreTotalAnneePers*2*$distance*0.136, 0, '', ' ')." kg&nbsp;&nbsp;&nbsp;</td>";
	echo "</tr>";
	echo "<tr class=\"gaucheTable\">";
	echo "<td>Gain du co-voiturage</td><td>Différence</td><td style=\"text-align:right;\">&nbsp;".number_format($nbreTotalAnneePers*2*$distance*0.136 - $nbreTotalAnneeVoit*2*$distance*0.136, 0, '', ' ')." kg&nbsp;&nbsp;&nbsp;</td>";
	echo "</tr>";
	echo "</table>\n";
	echo "<br><br>\n";
?>




</center>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php
	require "inc_foot.php5";
?>