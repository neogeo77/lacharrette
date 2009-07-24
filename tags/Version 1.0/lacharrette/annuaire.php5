<?php
	require "inc_commun.php5";
	
	$_SESSION['pageCourante']=$_SERVER['PHP_SELF'];		// nom de la page courante (exemple: "master.php5") La mémoriser permet de revenir sur cette page une fois l'identification (connecterMonCompte.php5) effectuée.
    $personnes=$calendrier->getPersonnesTrieesDateArrivee();

	// En tête et menu
	require "inc_head_et_menu.php5";
?>
<br><br><h1>ANNUAIRE</h1>

<center>

<br><br><br><br>
<?php
//***************** Ligne des titres *****************
echo '<table align=center class="tableCalendrier">';
echo "<tr class=\"hautTableAutreCouleur\">";
echo "<td>Tri.</td>";
echo "<td>Nom prénom</td>";
echo "<td>Voiture</td>";
echo "<td>Pl.</td>";
echo "<td>Tél. trav.</td>";
echo "<td>Tél. perso</td>";
echo "<td>Email</td>";
echo "</tr>";
		
//***************** Lignes des personnes *****************
$aujourdhui=$calendrier->aujourdhui();
foreach ($personnes as $personne)
{
	$dateArrivee=$personne->dateArrivee;
	$dateArrivee=calendrier::toDate($dateArrivee);
	$dateArrivee=date("d-m-Y", $dateArrivee);
	$nomPrenom=strtoupper($personne->nom)."<br>".ucfirst(strtolower($personne->prenom));
	if ($bEstConnecte)
	{
		$email1=$personne->email1;
		$telPersonnel=$personne->telPersonnel;
	}
	else
	{
		$email1="<a href=\"connecterMonCompte.php5\">&nbsp;Connectez vous pour&nbsp;<br>voir les emails&nbsp;</a>";
		$telPersonnel="<a href=\"connecterMonCompte.php5\">&nbsp;Connectez vous pour voir&nbsp;<br>les numéro de tél.&nbsp;</a>";
	}
	
	echo "<tr>";
	echo "<td nowrap class=\"gaucheTable\">$personne->trigramme</td>";
	echo "<td class=\"gaucheTable\">$nomPrenom</td>";
	echo "<td class=\"gaucheTable\">$personne->voiture</td>";
	echo "<td nowrap class=\"gaucheTable\">$personne->nbrePlaces</td>";
	echo "<td nowrap class=\"gaucheTable\">$personne->telTravail</td>";
	echo "<td nowrap class=\"gaucheTable\">$telPersonnel</td>";
	echo "<td nowrap class=\"gaucheTable\">$email1</td>";
	echo "</tr>\n";
}
echo "</table>\n";
?>

</center>


<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<?php
	require "inc_foot.php5";
?>