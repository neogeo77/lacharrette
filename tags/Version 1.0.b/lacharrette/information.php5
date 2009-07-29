<?php
	require "inc_commun.php5";
	$_SESSION['pageCourante']=$_SERVER['PHP_SELF'];		// nom de la page courante (exemple: "master.php5") La m�moriser permet de revenir sur cette page une fois l'identification (connecterMonCompte.php5) effectu�e.
    $personnes=$calendrier->getPersonnesTrieesDateArrivee();

	$urlBase="http://".$_SERVER['SERVER_NAME'].str_replace("information.php5", "", $_SERVER['PHP_SELF']);

	// En t�te et menu
	require "inc_head_et_menu.php5";
?>
<br><br><h1>INFORMATIONS DIVERSES</h1>

<center>

<br><br>
<hr width=90%>
<span class="titreInformation">Le lien � retenir</span>
<hr width=90%>
<a href="http://www.la-charrette.fr/">http://www.la-charrette.fr/</a>


<br><br><br><br>
<hr width=90%>
<span class="titreInformation">Les anciens de la charrette</span>
<hr width=90%>
<br>
<?php
	// Ligne des titres
	echo '<table align=center class="tableAnciens">';
	echo "<tr class=\"hautTableAutreCouleur\">";
	echo "<td>Tri.</td>";
	echo "<td>Nom pr�nom</td>";
	echo "<td>Voiture</td>";
	echo "<td>Pl.</td>";
	echo "<td>T�l. perso</td>";
	echo "<td>Email</td>";
	echo "<td>Date arriv�e</td>";
	echo "<td>Date d�part</td>";
	echo "<td>Cond.</td>";
	echo "<td>Pass.</td>";
	echo "</tr>";
	// Lignes des personnes
	$anciens=personne::chargerLesAnciens($codeTrajet);
	foreach ($anciens as $personne)
	{
		$trigramme=$personne->trigramme;
		$nomPrenom=strtoupper($personne->nom)."<br>".ucfirst(strtolower($personne->prenom));
		if ($bEstConnecte)
		{
			$email1=$personne->email1;
			$telPersonnel=$personne->telPersonnel;
		}
		else
		{
			$email1="<a href=\"connecterMonCompte.php5\">&nbsp;Connectez vous pour&nbsp;<br>voir les emails&nbsp;</a>";
			$telPersonnel="<a href=\"connecterMonCompte.php5\">&nbsp;Connectez vous pour voir&nbsp;<br>les num�ro de t�l.&nbsp;</a>";
		}
		$compteur=$calendrier->getCompteur($trigramme);
		$dateArrivee=$personne->dateArrivee;
		$dateArrivee=calendrier::toDate($dateArrivee);
		$dateArrivee=date("d-m-Y", $dateArrivee);
		$dateDepart=$personne->dateDepart;
		if ($dateDepart!="")
		{
			$dateDepart=calendrier::toDate($dateDepart);
			$dateDepart=date("d-m-Y", $dateDepart);
		}
		$nbreCDepart=$compteur->nbreCDepart;
		$nbrePDepart=$compteur->nbrePDepart;
		echo "<tr>";
		echo "<td class=\"gaucheTable\">$trigramme</td>";
		echo "<td class=\"gaucheTable\">$nomPrenom</td>";
		echo "<td class=\"gaucheTable\">$personne->voiture</td>";
		echo "<td nowrap class=\"gaucheTable\">$personne->nbrePlaces</td>";
		echo "<td nowrap class=\"gaucheTable\">$telPersonnel</td>";
		echo "<td class=\"gaucheTable\">$email1</td>";
		echo "<td nowrap class=\"gaucheTable\">$dateArrivee</td>";
		echo "<td nowrap class=\"gaucheTable\">$dateDepart</td>";
		echo "<td align=center class=\"gaucheTable\">$nbreCDepart</td>";
		echo "<td align=center class=\"gaucheTable\">$nbrePDepart</td>";
		echo "</tr>\n";
	}
	echo "</table>\n";
?>


<br><br><br><br>
<hr width=90%>
<span class="titreInformation">La page l�g�re (pour les t�l�phones)</span>
<hr width=90%>
<br>
<a href="tel.php5?CODETRAJET=<?php echo $codeTrajet;?>"><?php echo $urlBase."tel.php5?CODETRAJET=$codeTrajet";?></a>
<br><br>
<img src="http://chart.apis.google.com/chart?chs=150x150&cht=qr&chl=<?php echo $urlBase."tel.php5?CODETRAJET=$codeTrajet";?>&choe=UTF-8&chld=L|2">
<br><br>
<span style="font-size:10px;">
(C'est quoi ce truc ? En gros : avec un t�l�phone portable contenant un lecteur de "QR Code",<br>
prenez en photo ce "code-barres en 2 dimensions". Ce code contient l'adresse web ci-dessus.<br>
Cela permet d'�viter de taper "� la main" cette adresse sur le t�l�phone.<br>
Consultez <a target="_blank" style="font-size:10px;" href="http://fr.wikipedia.org/wiki/Code_QR">Wikipedia</a> pour plus d'information)
</span>


<br><br><br><br>
<hr width=90%>
<span class="titreInformation">Le Flux RSS (pour les t�l�phones)</span>
<hr width=90%>
<br>
<a href="rss.php5?CODETRAJET=<?php echo $codeTrajet;?>"><?php echo $urlBase."rss.php5?CODETRAJET=$codeTrajet";?></a>
<br><br>
<a target="_blank" style="font-size:10px;" href="http://fr.wikipedia.org/wiki/RSS_(format)">(c'est quoi un flux RSS ?)</a>
<br><br>
<img src="http://chart.apis.google.com/chart?chs=150x150&cht=qr&chl=<?php echo $urlBase."rss.php5?CODETRAJET=$codeTrajet";?>&choe=UTF-8&chld=L|2">


<br><br><br><br>
<hr width=90%>
<span class="titreInformation">L'int�gration dans votre calendrier</span>
<hr width=90%>
<?php
if ($bEstConnecte)
{
?>
	<br>
	Format iCal standard (Outlook,  Mozilla...) : <a href="ical.php5?trigramme=<?php echo $trigrammeUser;?>"><?php echo $urlBase."ical.php5?trigramme=$trigrammeUser";?></a><br><br>
	Format iCal sp�cifique pour Google Agenda : <a href="icalGoogle.php5?trigramme=<?php echo $trigrammeUser;?>"><?php echo $urlBase."icalGoogle.php5?trigramme=$trigrammeUser";?></a><br>
	<br><br>
	Ce lien vous permet d'ins�rer dans votre calendrier les jours pour lesquels vous �tes conducteur.<br>
	Le format utilis� est "iCal" (<a target="_blank" href="http://fr.wikipedia.org/wiki/ICalendar">standard</a> pour les �changes de donn�es de calendrier)<br>
	Il peut par exemple �tre utilis� dans Google Agenda (-> "ajouter un calendrier par URL")<br>
	<br>
<?php
}
else
{
?>
	<a href="connecterMonCompte.php5">Connectez vous pour voir le lien correspondant</a>
<?php
}
?>


<br><br><br><br>
<hr width=90%>
<span class="titreInformation">Lieu de rendez-vous</span>
<?php
	//***************** Plan d'acc�s *****************
	if (file_exists("img/$codeTrajet/planAcces.png"))
		$planAcces="<img src=\"img/$codeTrajet/planAcces.png\">";
	else
		$planAcces="<br><b><i>La carte n'a pas encore �t� cr��e.</i></b><br>";

	if ($calendrier->trajet->lienGoogleMap<>"")
	{
		$lienGoogle=$calendrier->trajet->lienGoogleMap;
		$lienGoogle="<a target=\"_blank\" href=\"$lienGoogle\">Lien Google Maps</a>";
	}
	else
	{
		$lienGoogle="";
	}
?>
<hr width=90%>
&nbsp;<?php echo $planAcces;?>&nbsp;
<br>
<?php echo $lienGoogle;?>


<br><br>
<?php
	//***************** Lien ancienne charrette *****************
	if ($calendrier->trajet->lienAncienneCharrette<>"")
	{
		$lienAncienneCharrette=$calendrier->trajet->lienAncienneCharrette;
		?>
		<br><br>
		<hr width=90%>
		<span class="titreInformation">Lien ancienne charrette</span>
		<hr width=90%>
		<br>
		<a target="_blank" href="<?php echo $lienAncienneCharrette;?>">Cliquer ici</a>
		<?php
	}
?>

</center>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php
	require "inc_foot.php5";
?>
