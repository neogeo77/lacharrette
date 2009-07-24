<?php
	require "inc_commun.php5";
	require "inc_loginNecessaire.php5";

	// En tête et menu
	require "inc_head_et_menu.php5";
?>
<script language="JavaScript">
function soumettre()
{
	if (document.getElementById("password").value != document.getElementById("passwordConf").value)
	{
		alert("Mots de passe différents !");
		return;
	}
	if (document.getElementById("password").value.length<5)
	{
		alert("Mot de passe trop court !");
		return;
	}
	document.formulaire.submit();
}
</script>

<br><br><h1>Modifier mon mot de passe - <?php echo $utilisateur->PrenomNOM();?></h1><br>
<br><br><br>

<form id="formulaire" name="formulaire" method="POST" action="modifierMotDePasse_trt.php5">
<center>
<table class="tableFormulaire">
<tr align=left><td nowrap width=200><b>Nouveau mot de passe</b></td> <td><input size=20 name="password" id="password" maxlength=50 type="password" value=""></td></tr>
<tr align=left><td nowrap width=200><b>Confirmez le mot de passe</b></td> <td><input size=20 name="passwordConf" id="passwordConf" maxlength=50 type="password" value=""></td></tr>
</table>
<br><br><br>
<input onclick="soumettre()" type="button" type="button" value="Enregistrer">&nbsp;&nbsp;&nbsp;<input type="button" onclick="window.location='monCompte.php5';" value="Annuler">
</center>
</form>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<script language="JavaScript" for="window" event="onLoad">
	document.getElementById('password').focus();
</script>

<?php
	require "inc_foot.php5";
?>