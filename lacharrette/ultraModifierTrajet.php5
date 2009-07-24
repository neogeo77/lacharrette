<?php
	require "inc_commun.php5";
	require "inc_ultraCharretteMasterNecessaire.php5";

	$trajetsSession=$_SESSION["trajets"];
	$codeTrajet=$_GET["trajet"];
	$trajet=$trajetsSession[$codeTrajet];

	// En tête et menu
	require "inc_head_et_menu.php5";
?>
<br><br><h1>MODIDIER TRAJET - <?php echo $trajet->titre;?></h1><br>
<br><br><br>

<form method="POST" action="ultraModifierTrajet_trt.php5">
<center>
<table class="tableFormulaire">
<tr align=left><td width=110><b>Libelle</b></td> <td><input size=50 name="titre"          maxlength=60 type="text" value="<?php echo htmlspecialchars($trajet->titre);?>"></td></tr>
<tr align=left><td width=110><b>Message par défaut</b></td> <td><textarea cols=50 rows=10 name="texteMsgDefaut"><?php echo $trajet->texteMsgDefaut;?></textarea></td></tr>
<tr align=left><td width=110><b>Lien Google Map</b></td> <td><input size=50 name="lienGoogleMap" maxlength=255 type="text" value="<?php echo $trajet->lienGoogleMap;?>"></td></tr>
<tr align=left><td width=110><b>Lien Ancienne Charrette</b></td> <td><input size=50 name="lienAncienneCharrette" maxlength=255 type="text" value="<?php echo $trajet->lienAncienneCharrette;?>"></td></tr>
<tr align=left><td width=110><b>Trajet lié</b></td> <td><input size=3 name="trajetLie"  maxlength=3 type="text" value="<?php echo $trajet->trajetLie;?>"></td></tr>
<tr align=left><td width=110><b>Couleur</b></td> <td><input size=9 name="couleur"        maxlength=7 type="text" value="<?php echo $trajet->couleur;?>"></td></tr>
<tr align=left><td width=110><b>Ordre</b></td> <td><input size=3 name="ordre"            maxlength=3 type="text" value="<?php echo $trajet->ordre;?>"></td></tr>
</table>
<br><br><br>
<input type="submit" value="Enregistrer">&nbsp;&nbsp;&nbsp;<input type="button" onclick="window.location='ultraCharretteMaster.php5';" value="Annuler">
</center>
</form>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php
	require "inc_foot.php5";
?>