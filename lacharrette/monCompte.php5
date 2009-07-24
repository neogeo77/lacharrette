<?php
	require "inc_commun.php5";
	require "inc_loginNecessaire.php5";

	// En tête et menu
	require "inc_head_et_menu.php5";
?>
<br><br><h1>MON COMPTE - <?php echo $utilisateur->PrenomNOM();?> - <?php echo $calendrier->trajet->titre;?></h1><br>
<br><br><br>
<center>
<a href="absences.php5">Mes absences/convenances/exigences</a><br><br>
<?php
if (isset($trajetLie)) 
{
	echo "<a href=\"choisirCharretteAbsences_trt.php5?trajet=$trajetLie->codeTrajet\">Mes absences/convenances/exigences sur la charrette liée <font color=red>(new !)</font></a><br><br>";
}
?>
<br>
<a href="modifierInfoPerso.php5">Modifier mes informations perso</a><br><br>
<a href="modifierMotDePasse.php5">Modifier mon mot de passe</a><br><br>
<a href="changerUtilisateur_trt.php5">Je ne suis pas <?php echo $utilisateur->PrenomNOM();?> (se déconnecter)</a><br><br>
</center>
<br><br>
<?php
if ($utilisateur->estMotDePasseParDefaut()) echo "<center><b><font color=\"red\">Votre mot de passe est toujours le mot de passe par défaut.<br>Pensez à le changer !</font></b><br><br><br><br></center>";
?>
<br>
<table width=450 align=center class="tableFormulaire">
<tr><td width=120><b>Nom</b></td><td><?php echo strtoupper($utilisateur->nom);?></td></tr>
<tr><td><b>Prénom</b></td><td><?php echo ucfirst(strtolower($utilisateur->prenom));?></td></tr>
<tr><td><b>Caisse</b></td><td><?php echo $utilisateur->voiture;?></td></tr>
<tr><td><b>Places</b></td><td><?php echo $utilisateur->nbrePlaces;?></td></tr>
<tr><td><b>Email</b></td><td><?php echo $utilisateur->email1;?></td></tr>
<tr><td><b>Tél. personnel</b></td><td><?php echo $utilisateur->telPersonnel;?></td></tr>
<tr><td><b>Tél. travail</b></td><td><?php echo $utilisateur->telTravail;?></td></tr>
<tr><td><b>Date arrivée</b></td><td><?php echo $utilisateur->dateArrivee;?></td></tr>
<tr><td><b>Date départ</b></td><td><?php echo $utilisateur->dateDepart;?></td></tr>
</table>


<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php
	require "inc_foot.php5";
?>