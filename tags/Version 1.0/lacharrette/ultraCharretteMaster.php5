<?php
	require "inc_commun.php5";
	require "inc_ultraCharretteMasterNecessaire.php5";

	$trajets=trajet::chargerTout();

	// En tête et menu
	require "inc_head_et_menu.php5";
?>
<center>
<br><br><h1>ULTRA CHARRETTE MASTER</h1>
<br><br><br><br>
<a href="ultraOptimiserBase.php5">Optimiser la base</a><br><br>
<a href="ultraAjouterTrajet.php5">Ajouter un nouveau trajet</a><br><br>
<br><br>
<table width=450 border=0 class="tableCalendrier">
<tr class="hautTableAutreCouleur">
<td>Ordre</td>
<td>Trajet</td>
<td>Code</td>
<td>Action</td>
</tr>
<?php
$trajetsSession=array();
foreach ($trajets as $trajet)
{
	echo "<tr>";
	echo "<td nowrap class=\"gaucheTable\">$trajet->ordre</td>";
	echo "<td nowrap class=\"gaucheTable\" style=\"text-align:left;\">&nbsp;<span style=\"color:$trajet->couleur;\">$trajet->titre</span></td>";
	echo "<td nowrap class=\"gaucheTable\">$trajet->codeTrajet</td>";
	echo "<td nowrap class=\"gaucheTable\"><a href=\"ultraModifierTrajet.php5?trajet=$trajet->codeTrajet\">&nbsp;&nbsp;Modifier&nbsp;&nbsp;</a></td>";
	echo "</tr>";
	$trajetsSession[$trajet->codeTrajet]=$trajet;
}
$_SESSION["trajets"]=$trajetsSession;
?>
</table>



</center>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<?php
	require "inc_foot.php5";
?>