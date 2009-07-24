<?php
	require "inc_commun.php5";
	require "inc_loginNecessaire.php5";
	$personnes=personne::chargerToutSauf($codeTrajet);
	unset($_SESSION['NEW_UTILISATEUR']);
	unset($_SESSION['ERREUR_SQL']);
	// En tête et menu
	require "inc_head_et_menu.php5";
?>
<br><br><h1>AJOUTER UNE PERSONNE EXISTANTE EN BASE</h1><br>
<br><br><br>
<center>
<?php
//***************** Ligne des titres *****************
echo '<table width=400 align=center class="tableCalendrier">';
echo "<tr class=\"hautTableAutreCouleur\">";
echo "<td>Tri.</td>";
echo "<td>Nom prénom</td>";
echo "<td> </td>";
echo "</tr>";
		
//***************** Lignes des personnes *****************
foreach ($personnes as $personne)
{
	echo "<tr>";
	echo "<td nowrap class=\"gaucheTable\">$personne->trigramme</td>";
	echo "<td nowrap class=\"gaucheTable\">".$personne->NOMPrenom()."</td>";
	echo "<td nowrap class=\"gaucheTable\"><a href=\"relierMembres_trt.php5?trigramme=$personne->trigramme\">Relier</a></td>";
	echo "</tr>\n";
}
echo "</table>\n";
?>
<br><br>
<a href="gererMembres.php5">Annuler</a>
</center>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php
	require "inc_foot.php5";
?>