<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
<TITLE>
<?php 
if (isset($calendrier))
	echo $calendrier->trajet->titre;
else
	echo "La Charrette";
?>
</TITLE>
<script type="text/javascript" src="dynMenu.js"></script>
<script type="text/javascript" src="browserdetect.js"></script>
<meta name="keywords" content="yann, deydier, co voiturage, co-voiturage, covoiturage, charrette, orléans, esvres, charrette45">
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<meta http-equiv="imagetoolbar" content="no">
<link rel="icon" type="image/png" href="img/icone.png" />
<link rel="shortcut icon" type="image/x-icon" href="img/icone.ico" />
<LINK media="screen" href="style.css" type="text/css" rel="stylesheet">
<LINK media=screen href="menu.css" type=text/css rel=stylesheet>
<script language="JavaScript">
function ajouterAuxFavoris()
{
	if ( navigator.appName != 'Microsoft Internet Explorer' )
	{
		alert("Cette action ne fonctionne bien que sur Internet Explorer.\nAvec les autres navigateurs, merci d'ajouter http://www.la-charrette.fr (et pas autre chose) à vos favoris.");
	}
	else
	{
		window.external.AddFavorite('http://www.la-charrette.fr/','La Charrette')
	}
	return true;
}
</script>
</HEAD>
<BODY>
<table width="900" style="background-color:#FFFFFF;" border=0 cellpadding=0 cellspacing=0 align=center>

<!-- Bandeau haut -->
<tr><td width="900" colspan=3><img border=0 src="img/bandeauHaut.jpg"></td></tr>

<!-- Menu -->
<tr><td width="900" colspan=3>
<table class="tableMenu" width=100%>
<tr>
<td width=90%>
<ul class="menu" id="menu">
    <li><a href="index.php5">Accueil</a></li>
    <li><a href="monCompte.php5">Mon compte</a>
        <ul>
            <li><a href="absences.php5">Mes absences/exigences</a></li>
            <li><a href="modifierInfoPerso.php5">Mes infos perso</a></li>
            <li><a href="modifierMotDePasse.php5">Changer mon mot de passe</a></li>
            <li><a href="changerUtilisateur_trt.php5">Se deconnecter</a></li>
        </ul>
    </li>
    <li><a href="master.php5">Charrette Master</a></li>
    <li><a style="cursor: default;" href="#">Information</a>
        <ul>
            <li><a href="reglement.php5">Règlement</a></li>
            <li><a href="annuaire.php5">Annuaire</a></li>
            <li><a href="historique.php5">Historique</a></li>
            <li><a href="statistiques.php5">Statistiques &nbsp;&nbsp;<i style="FONT-SIZE:11px;color:#FF0000;">New !</i></a></li>
            <li><a href="information.php5">Autres infos.</a></li>
        </ul>
    </li>
    <li><a style="cursor: default;" href="#">Divers</a>
        <ul>
            <li><a href="galerie.php5">Galerie photo &nbsp;&nbsp;<i style="FONT-SIZE:11px;color:#FF0000;">New !</i></a></li>
            <li><a href="forum.php5">Le forum &nbsp;&nbsp;<i style="FONT-SIZE:11px;color:#FF0000;">New !</i></a></a></li>
            <li><a href="changerDeCharrette_trt.php5">Accéder aux autres charrettes</a></li>
            <?php if (isset($trajetLie)) {echo "<li><a href=\"choisirCharrette_trt.php5?trajet=$trajetLie->codeTrajet\">Accéder à \"$trajetLie->titre\"</a></li>";}?>
            <li><a href="javascript:void(ajouterAuxFavoris());">Ajouter ce site à mes favoris</a></li>
            <li><a href="contact.php5">About / Contact</a></li>
            <?php if ($bEstConnecte && $utilisateur->estUltraMaster()) echo "<li><a href=\"ultraCharretteMaster.php5\">Ultra Master</a></li>";?>
        </ul>
    </li>
</ul>
</td>
<td nowrap width=10%>
<?php
if ($bEstConnecte)
{
	echo "<b>Connecté : $trigrammeUser&nbsp;&nbsp;</b>";
	if ($utilisateur->estAncien) echo "<b>(ancien)</b>";
}
else
{
	echo "<b>Non connecté&nbsp;&nbsp;</b>";
}
?>
</td>
</tr>
</table>
</td></tr>

<!-- Page -->
<tr style="background-image: url(img/carteFond.jpg);" valign=top>
<td width="20"><img src="img/1pix.gif" width=20></td>
<td width="860" style="text-align:justify;">