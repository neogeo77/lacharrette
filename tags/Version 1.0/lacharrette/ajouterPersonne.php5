<?php
	require "inc_commun.php5";
	require "inc_loginNecessaire.php5";
	if (isset($_SESSION['NEW_UTILISATEUR']))
	{
   		$newUtilisateur=$_SESSION['NEW_UTILISATEUR'];
   	}
   	else
   	{
   		$newUtilisateur=new personne;
   		$newUtilisateur->dateArrivee=date("Y-m-d", calendrier::aujourdhui());
   	}
   	
   	$messageErreur="";
   	if (isset($_SESSION['ERREUR_SQL']))
   	{
   		$err=$_SESSION['ERREUR_SQL'];
   		if ($err=="CLE_EN_DOUBLE")
   		{
   			$messageErreur="Trigramme déjà existant !";
   		}
   		else
   		{
   			$messageErreur=$err;
   		}
   	}

	// En tête et menu
	require "inc_head_et_menu.php5";
?>
<br><br><h1>AJOUTER UNE PERSONNE DANS LA CHARRETTE</h1><br>
<form method="POST" action="ajouterPersonne_trt.php5">
<center>
<table class="tableFormulaire">
<?php
if ($messageErreur<>"") echo "<tr><td align=center height=20 colspan=2><font color=red><b>$messageErreur</b></font></td></tr>";
?>
<tr align=left><td width=110><b>Trigramme</b></td> <td><input size=3 name="trigramme" maxlength=3 type="text" value="<?php echo htmlspecialchars(strtoupper($newUtilisateur->trigramme));?>"></td></tr>
<tr align=left><td><b>Mot de passe</b></td> <td><input size=20 name="password" maxlength=50 type="text" value="<?php echo htmlspecialchars(strtoupper($newUtilisateur->password));?>"></td></tr>
<tr align=left><td><b>Nom</b></td> <td><input size=50 name="nom"          maxlength=50 type="text" value="<?php echo htmlspecialchars(strtoupper($newUtilisateur->nom));?>"></td></tr>
<tr align=left><td><b>Prénom</b></td>        <td><input size=50 name="prenom"       maxlength=50 type="text" value="<?php echo htmlspecialchars(ucfirst(strtolower($newUtilisateur->prenom)));?>"></td></tr>
<tr align=left><td><b>Caisse</b></td>        <td><input size=50 name="voiture"      maxlength=50 type="text" value="<?php echo htmlspecialchars(ucfirst(strtolower($newUtilisateur->voiture)));?>"></td></tr>
<tr align=left><td><b>Places</b></td>        <td><input size=2  name="nbrePlaces"   maxlength=2  type="text" value="<?php echo $newUtilisateur->nbrePlaces;?>"></td></tr>
<tr align=left><td><b>Email</b></td>         <td><input size=50 name="email1"       maxlength=50 type="text" value="<?php echo htmlspecialchars($newUtilisateur->email1);?>"></td></tr>
<tr align=left><td><b>Tél. personnel</b></td><td><input size=20 name="telPersonnel" maxlength=50 type="text" value="<?php echo $newUtilisateur->telPersonnel;?>"></td></tr>
<tr align=left><td><b>Tél. travail</b></td>  <td><input size=20 name="telTravail"   maxlength=50 type="text" value="<?php echo $newUtilisateur->telTravail;?>"></td></tr>
<tr align=left><td><b>Date arrivée</b></td>  <td><input size=11 name="dateArrivee"  maxlength=10 type="text" value="<?php echo $newUtilisateur->dateArrivee;?>">&nbsp;&nbsp;&nbsp;(AAAA-MM-JJ)</td></tr>
</table>
<br><br>
<input type="submit" value="Ajouter">&nbsp;&nbsp;&nbsp;<input type="button" onclick="window.location='gererMembres.php5';" value="Annuler">
</form>
<br>
<font color=red><b>Remarque :</b></font> les compteurs (nombre de P et nombre de C) attribués à cette personne<br>seront la moyenne de ceux des personnes déjà présentes dans la charrette.
</center>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php
	require "inc_foot.php5";
?>