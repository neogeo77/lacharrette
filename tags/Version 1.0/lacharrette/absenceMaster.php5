<?php
	require "inc_commun.php5";
	require "inc_loginNecessaire.php5";

   	$trigrammeAbsent=$_GET["trigramme"];
   	$utilisateurAbsent=$calendrier->getPersonne($trigrammeAbsent);

   	$joursParLigne=21;
   	$joursAvant=calendrier::deltaCompteurs+1;
   	$joursApres=$joursParLigne*3;

   	$_SESSION['joursAvant']=$joursAvant;
   	$_SESSION['joursApres']=$joursApres;

	// En tête et menu
	require "inc_head_et_menu.php5";
?>
<script language="JavaScript">
function couleurCombo(combo)
{
	couleur=combo.options[combo.selectedIndex].style.backgroundColor;
	combo.style.backgroundColor=couleur;
	combo.parentNode.style.backgroundColor=couleur;
	document.getElementById("tableCalendrier").focus();	// uniquement pour enlever le focus de la combobox
}
</script>

<br><br><h1>ABSENCES - <?php echo $utilisateurAbsent->PrenomNOM();?></h1><br><br>
<br><br><br>

<form method="POST" action="absenceMaster_trt.php5">


<?php

for ($debut=$joursAvant;$debut<$joursApres;$debut+=$joursParLigne)
{
	echo '<table class="tableCalendrier">';
	echo "<tr class=\"hautTable\">";
	for ($jour=$debut;$jour<$debut+$joursParLigne && $jour<$joursApres;$jour++)
	{
		$dt_jour = calendrier::aujourdhuiPlus($jour);
		if ($calendrier->estWeekEndOuFerie($dt_jour)) $classe="cellWeekEnd"; else $classe = "cellSemaine";
		echo '<td align=center nowrap class="'.$classe.'">';
		echo ucfirst(nomDuJourAbrege($dt_jour)).'<br>'.date("d", $dt_jour).'<br>'.nomDuMoisAbrege(date("n", $dt_jour));
		echo '</td>';
	}
	echo "</tr>\n";

	echo "<tr>\n";
	for ($jour=$debut;$jour<$debut+$joursParLigne && $jour<$joursApres;$jour++)
	{
		$date = calendrier::aujourdhuiPlus($jour);
		if ($calendrier->estWeekEndOuFerie($date))
		{
			echo '<td align=center class="cellWeekEnd"></td>';
		}
		else
		{
			$code = $calendrier->getCode($date, $trigrammeAbsent);
			$couleur=couleurJourHTMLFutur($code);
			if (($code=='C')) $vide=""; else $vide="P"; // $vide="" signifie "ne rien modifier" (voir class.calendrier.php5, fonction setCode())
			$nomHTML = nomHTML('cbo', $trigrammeAbsent, $date);
			echo '<td align=center bgcolor="'.$couleur.'">';
			echo '<select name="'.$nomHTML.'" style="background-color:'.$couleur.';" onchange="couleurCombo(this);">';
			echo '<option style="background-color:'.couleurJourHTML('' , $date).';" value="'.$vide.'"> </option>';
			echo '<option '.($code=="A"?"selected ":"").'style="background-color:'.couleurJourHTMLFutur('A').';" value="A">A</option>';
			echo '<option '.($code=="O"?"selected ":"").'style="background-color:'.couleurJourHTMLFutur('O').';" value="O">O</option>';
			echo '<option '.($code=="V"?"selected ":"").'style="background-color:'.couleurJourHTMLFutur('V').';" value="V">V</option>';
			echo '<option '.($code=="I"?"selected ":"").'style="background-color:'.couleurJourHTMLFutur('I').';" value="I">I</option>';
			echo '</select>';
			echo '</td>';
		}
	}
	echo "</tr>\n";
	echo "</table>\n";
	echo "<br>";
}
?>
<br>
<center>
<input type="submit" value="Enregistrer">&nbsp;&nbsp;&nbsp;<input type="button" onclick="window.location='master.php5';" value="Annuler">
</center>
<br><br>
<table align=center class="tableLegende" id="tableLegende">
<tr><td align=center bgcolor="<?php echo couleurJourHTMLFutur('A');?>">A</td><td align=left>Absent</td></tr>
<tr><td align=center bgcolor="<?php echo couleurJourHTMLFutur('O');?>">O</td><td align=left>Convenance</td></tr>
<tr><td align=center bgcolor="<?php echo couleurJourHTMLFutur('V');?>">V</td><td align=left>Voiture nécessaire (conducteur imposé)</td></tr>
<tr><td align=center bgcolor="<?php echo couleurJourHTMLFutur('I');?>">I</td><td align=left>Voiture indisponible (conducteur interdit)</td></tr>
</table>
<br><br><br><br><br><br>

<input type="hidden" name="trigramme" value="<?php echo $trigrammeAbsent; ?>">

</form>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<?php
	require "inc_foot.php5";
?>