<?php
	require "inc_commun.php5";
	require "inc_loginNecessaire.php5";

	$trigrammeUrl=$_GET['trigramme'];
	$user=$calendrier->getPersonne($trigrammeUrl);
	$aujourdhui=$calendrier->aujourdhui();
	$nbreC=$calendrier->nbreCPourDate($trigrammeUrl, $aujourdhui);
	$nbreP=$calendrier->nbrePPourDate($trigrammeUrl, $aujourdhui);
	$reportNbreC=$calendrier->getCompteur($trigrammeUrl)->reportNbreC;
	$reportNbreP=$calendrier->getCompteur($trigrammeUrl)->reportNbreP;

	// En tête et menu
	require "inc_head_et_menu.php5";
?>
<br><br><h1>MODIFIER COMPTEURS</h1><br>
<br><br><br>

<form method="POST" action="modifierCompteurs_trt.php5">
<center>
<table class="tableFormulaire">
<tr align=left><td width=110><b>Nom</b></td> <td><?php echo htmlspecialchars(strtoupper($user->nom));?></td></tr>
<tr align=left><td><b>Prénom</b></td>        <td><?php echo htmlspecialchars(ucfirst(strtolower($user->prenom)));?></td></tr>
<tr align=left><td><b>Nombre conducteurs</b></td>       <td><input size=4 maxlength=4 name="nbreC"       type="text" value="<?php echo $nbreC;?>"></td></tr>
<tr align=left><td><b>Nombre passagers</b></td>         <td><input size=4 maxlength=4 name="nbreP"       type="text" value="<?php echo $nbreP;?>"></td></tr>
<tr align=left><td><b>Report nombre conducteurs</b></td><td><input size=4 maxlength=4 name="reportNbreC" type="text" value="<?php echo $reportNbreC;?>"></td></tr>
<tr align=left><td><b>Report nombre passagers</b></td>  <td><input size=4 maxlength=4 name="reportNbreP" type="text" value="<?php echo $reportNbreP;?>"></td></tr>
</table>
<br><br><br>
<input type="hidden" value="<?php echo $trigrammeUrl;?>" name="trigramme">
<input type="submit" value="Enregistrer">&nbsp;&nbsp;&nbsp;<input type="button" onclick="window.location='gererMembres.php5';" value="Annuler">
</center>
</form>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php
	require "inc_foot.php5";
?>