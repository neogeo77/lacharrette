<?php
	require "inc_commun.php5";
	require "inc_loginNecessaire.php5";
	$personnes=$calendrier->getPersonnesTrieesNom();
	$aujourdhui=$calendrier->aujourdhui();
	unset($_SESSION['NEW_UTILISATEUR']);
	unset($_SESSION['ERREUR_SQL']);
	// En tête et menu
	require "inc_head_et_menu.php5";
?>
<br><br><h1>GERER LES MEMBRES DE LA CHARRETTE</h1><br>
<br><br>
<script language="JavaScript">
function confirmeSuppression(trigramme)
{
	ret=confirm("Confirmez-vous cette suppression de " + trigramme + " ?\nRemarque: seul le lien vers cette charrette sera supprimé.");
	if (ret) window.location="supprimerPersonne_trt.php5?trigramme="+trigramme;
}
</script>
<center>
<a href="ajouterPersonne.php5">Ajouter une nouvelle personne</a><br><br>
<a href="relierMembres.php5">Ajouter une personne déjà présente en base</a><br><br>
<br>
<?php
//***************** Ligne des titres *****************
echo '<table width=400 align=center class="tableCalendrier">';
echo "<tr class=\"hautTableAutreCouleur\">";
echo "<td>Tri.</td>";
echo "<td>Nom prénom</td>";
echo "<td>Cond.</td>";
echo "<td>Pass.</td>";
echo "<td colspan=3>Actions</td>";
echo "</tr>";
		
//***************** Lignes des personnes *****************
foreach ($personnes as $personne)
{
	$nbreP=$calendrier->nbrePPourDate($personne->trigramme, $aujourdhui);
	$nbreC=$calendrier->nbreCPourDate($personne->trigramme, $aujourdhui);
	echo "<tr>";
	echo "<td nowrap class=\"gaucheTable\">$personne->trigramme</td>";
	echo "<td nowrap class=\"gaucheTable\">".$personne->NOMPrenom()."</td>";
	echo "<td nowrap class=\"gaucheTable\">$nbreC</td>";
	echo "<td nowrap class=\"gaucheTable\">$nbreP</td>";
	echo "<td nowrap class=\"gaucheTable\"><a href=\"javascript:confirmeSuppression('$personne->trigramme')\">&nbsp;&nbsp;Supprimer&nbsp;&nbsp;</a></td>";
	echo "<td nowrap class=\"gaucheTable\"><a href=\"modifierCompteurs.php5?trigramme=$personne->trigramme\">&nbsp;&nbsp;Modifier compteurs&nbsp;&nbsp;</a></td>";
	echo "<td nowrap class=\"gaucheTable\"><a href=\"modifierInfoPersoMaster.php5?trigramme=$personne->trigramme\">&nbsp;&nbsp;Modifier infos persos&nbsp;&nbsp;</a></td>";
	echo "</tr>\n";
}
echo "</table>\n";
?>

</center>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php
	require "inc_foot.php5";
?>