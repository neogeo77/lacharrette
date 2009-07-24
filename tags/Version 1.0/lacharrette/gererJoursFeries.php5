<?php
	require "inc_commun.php5";
	require "inc_loginNecessaire.php5";
	$joursFeries=$calendrier->joursFeries;
	// En tête et menu
	require "inc_head_et_menu.php5";
?>
<br><br><h1>GERER LES JOURS FERIES</h1><br>
<br><br>
<script language="JavaScript">
function confirmeSuppression(jour)
{
	ret=confirm("Confirmez-vous cette suppression du jour férié " + jour + " ?\n\nATTENTION ! Supprimer un jour férié passé peut influer sur les compteurs !");
	if (ret) window.location="supprimerJourFerie_trt.php5?joursFerie="+jour;
}
</script>
<center>

<form method=post action="ajouterJourFerie_trt.php5">
<table width=400 class="tableFormulaire">
<tr><td align=center>
<br>
<b>Ajouter un nouveau jour férié</b>
<br><br>
<input type=text size=2 maxlength=2 name="jour" value=""> - <input type=text size=2 maxlength=2 name="mois" value=""> - <input type=text size=4 maxlength=4 name="annee" value="">
<br>
(jj-mm-aaaa)
<br><br>
<input type="submit" value="Ajouter">
<br><br>
</td></tr>
</table>
</form>

<br><br>



<br>
<?php
//***************** Ligne des titres *****************
echo '<table width=400 align=center class="tableCalendrier">';
		
//***************** Lignes des personnes *****************
foreach ($joursFeries as $jourFerie)
{
	$dt_jourFerie=calendrier::toDate($jourFerie);
	echo "<tr>";
	echo "<td nowrap class=\"gaucheTable\">".ucfirst(nomDuJourAbrege($dt_jourFerie)).". ".date("d-m-Y", $dt_jourFerie)."</td>";
	echo "<td nowrap class=\"gaucheTable\"><a href=\"javascript:confirmeSuppression('$jourFerie')\">&nbsp;&nbsp;Supprimer&nbsp;&nbsp;</a></td>";
	echo "</tr>\n";
}
echo "</table>\n";
?>

</center>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php
	require "inc_foot.php5";
?>