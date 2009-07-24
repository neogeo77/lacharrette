<?php
	require_once "inc_commun.php5";
	$motDePasseIncorrect = isset($_GET["motDePasseIncorrect"]);

	// En tête et menu
	require "inc_head_et_menu.php5";
?>
<script language=javascript>
function changeSelection(cbo)
{
	if(cbo.value=="ancien")
	{
		document.getElementById("trAncien").style.display="";
	}
	else
	{
		document.getElementById("trAncien").style.display="none";
	}
}
</script>

<br><br><h1>CONNEXION</h1><br><br>

<form method="POST" action="connecterMonCompte_trt.php5">


<table align=center class="tableFormulaire">
<tr><td height=20 colspan=4></td></tr>
<?php
if ($motDePasseIncorrect) echo "<tr><td align=center height=20 colspan=4><font color=red><b>Mot de passe incorrect !</b></font></td></tr>";
?>
<tr>
<td width=20></td>
<td>Vous êtes</td>
<td align=left>
<select onchange="changeSelection(this)" id="cboPersonne" name="cboPersonne">
<option value="">- Sélectionner -</option>
<?php
$personnes=$calendrier->getPersonnesTrieesNom();
if (isset($_GET["trigrammeErreur"])) $trigrammeErreur=$_GET["trigrammeErreur"]; else $trigrammeErreur="";
foreach ($personnes as $personne)
{
	$trigramme=$personne->trigramme;
	$prenomNom=$personne->NOMPrenom();
	if ($trigrammeErreur==$trigramme) $selected="selected"; else $selected="";
	echo "<option $selected value=\"$trigramme\">$prenomNom</option>\n";
}
?>
<option value="ancien">* Un ancien de la charrette *</option>
</select>
</td>
<td width=20></td>
</tr>
<tr style="display:none" id="trAncien">
<td width=20></td>
<td>Vous êtes l'ancien</td>
<td align=left>
<select name="cboPersonneAncienne">
<option value="">- Sélectionner -</option>
<?php
$anciens=$calendrier->getAnciennesPersonnesTrieesNom();
foreach ($anciens as $personne)
{
	$trigramme=$personne->trigramme;
	$prenomNom=$personne->NOMPrenom();
	echo "<option value=\"$trigramme\">$prenomNom</option>\n";
}
?>
</select>
</td>
<td width=20></td>
</tr>
<tr>
<td width=20></td>
<td>Mot de passe<br></td>
<td align=left><input size="20" maxlength="50" type="password" id="password" name="password"><br><a style="FONT-SIZE:9px;" href="passwordOublie.php5">Oublié ?</a></td>
<td width=20></td>
</tr>
<tr>
<td width=20></td>
<td>Se souvenir de moi</td>
<td align=left><input checked type="checkbox" name="chkSouvenir"></td>
<td width=20></td>
</tr>
<tr><td height=60 align=center colspan=4><input type="submit" value="Se connecter"></td></tr>
</table>
</form>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<script language="JavaScript" for="window" event="onLoad">
	document.getElementById('cboPersonne').focus();
</script>

<?php
	require "inc_foot.php5";
?>