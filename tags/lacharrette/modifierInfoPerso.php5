<?php
	require "inc_commun.php5";
	require "inc_loginNecessaire.php5";

	// En tête et menu
	require "inc_head_et_menu.php5";
?>
<br><br><h1>MON COMPTE - <?php echo $utilisateur->PrenomNOM();?></h1><br>
<br><br><br>

<form method="POST" action="modifierInfoPerso_trt.php5">
<center>
<table class="tableFormulaire">
<tr align=left><td width=110><b>Nom</b></td> <td><input size=50 name="nom"          maxlength=50 type="text" value="<?php echo htmlspecialchars(strtoupper($utilisateur->nom));?>"></td></tr>
<tr align=left><td><b>Prénom</b></td>        <td><input size=50 name="prenom"       maxlength=50 type="text" value="<?php echo htmlspecialchars(ucfirst(strtolower($utilisateur->prenom)));?>"></td></tr>
<tr align=left><td><b>Caisse</b></td>        <td><input size=50 name="voiture"      maxlength=50 type="text" value="<?php echo htmlspecialchars(ucfirst(strtolower($utilisateur->voiture)));?>"></td></tr>
<tr align=left><td><b>Places</b></td>        <td><input size=2  name="nbrePlaces"   maxlength=2  type="text" value="<?php echo $utilisateur->nbrePlaces;?>"></td></tr>
<tr align=left><td><b>Email</b></td>         <td><input size=50 name="email1"       maxlength=50 type="text" value="<?php echo htmlspecialchars($utilisateur->email1);?>"></td></tr>
<tr align=left><td><b>Tél. personnel</b></td><td><input size=20 name="telPersonnel" maxlength=50 type="text" value="<?php echo $utilisateur->telPersonnel;?>"></td></tr>
<tr align=left><td><b>Tél. travail</b></td>  <td><input size=20 name="telTravail"   maxlength=50 type="text" value="<?php echo $utilisateur->telTravail;?>"></td></tr>
<tr align=left><td><b>Date arrivée</b></td>  <td><input size=11 name="dateArrivee"  maxlength=10 type="text" value="<?php echo $utilisateur->dateArrivee;?>">&nbsp;&nbsp;&nbsp;(AAAA-MM-JJ)</td></tr>
<tr align=left><td><b>Date départ</b></td>   <td><input size=11 name="dateDepart"   maxlength=10 type="text" value="<?php echo $utilisateur->dateDepart;?>">&nbsp;&nbsp;&nbsp;(AAAA-MM-JJ)</td></tr>
</table>
<br><br><br>
<input type="submit" value="Enregistrer">&nbsp;&nbsp;&nbsp;<input type="button" onclick="window.location='monCompte.php5';" value="Annuler">
</center>
</form>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php
	require "inc_foot.php5";
?>