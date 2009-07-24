<?php
	require_once "inc_commun.php5";
    $personnes=$calendrier->getPersonnesTrieesNom();

	// En tête et menu
	require "inc_head_et_menu.php5";
?>
<br><br><h1>MOT DE PASSE OUBLIE</h1><br><br>

<form method="POST" action="passwordOublie_trt.php5">

<table align=center class="tableFormulaire">
<tr><td height=20 align=center colspan=4></td></tr>
<tr>
<td width=20></td>
<td>Vous êtes</td>
<td align=left>
<select name="cboPersonne">
<?php
if (isset($_GET["trigrammeErreur"])) $trigrammeErreur=$_GET["trigrammeErreur"]; else $trigrammeErreur="";
foreach ($personnes as $personne)
{
	$trigramme=$personne->trigramme;
	$prenomNom=$personne->NOMPrenom();
	if ($trigrammeErreur==$trigramme) $selected="selected"; else $selected="";
	echo "<option $selected value=\"$trigramme\">$prenomNom</option>\n";
}
?>
</select>
</td>
<td width=20></td>
</tr>
<tr><td height=60 align=center colspan=4><input type="submit" value="Envoyer mon mot de passe par mail"></td></tr>
</table>
</form>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<?php
	require "inc_foot.php5";
?>