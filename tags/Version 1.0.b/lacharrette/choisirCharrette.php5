<?php
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	require_once "class.trajet.php5";
	session_start();
	$trajets=trajet::chargerTout();
	$bEstConnecte=false;

	// En tête et menu
	require "inc_head_et_menu.php5";
?>
<script language="JavaScript">
	trMenu=document.getElementById("menu");
	trMenu.style.display="none";
</script>

<br><br><br><br>
<center>
<div class="titreCharrette">Choisissez une charrette</div>
<br><br><br><br><br><br>

<br><br>
<table width=700 border=0>
<?php
foreach ($trajets as $trajet)
{
	echo "<tr><td align=center><a style=\"color:$trajet->couleur;\" href=\"choisirCharrette_trt.php5?trajet=$trajet->codeTrajet\">$trajet->titre</a></td></tr>";
	echo "<tr><td>&nbsp;</td></tr>";
}
?>
</table>
<br><br><br><br><br><br><br>
<table width=60% align=center>
<tr>
<td align=left>
<b>
Ce site permet de gérer du co-voiturage quotidien.<br>
L'équité entre les membres est assurée par le calcul d'un ratio "nombre de fois conducteur / nombre de fois passagers".<br>
Le lien à retenir est http://www.la-charrette.fr.<br>
Si vous souhaitez gérer votre co-voiturage avec ce site, n'hésitez pas à me contacter :
</b>
<br><br>
<center><img src="img/contactSeul.gif"></center>
</td>
</tr>
</table>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

</center>
<div style="display:none">
yann, deydier, co voiturage, co-voiturage, covoiturage, charrette, orléans, esvres, charrette45
</div>

<?php
	require "inc_foot.php5";
?>