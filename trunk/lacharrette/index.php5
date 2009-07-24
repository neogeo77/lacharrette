<?php
	require_once "inc_commun.php5";
	require_once "class.html.php5";

	// Nombre de jours avant et après
	if (!isset($_SESSION['joursDebut'])) $_SESSION['joursDebut']="-2";
	$joursDebut=intval($_SESSION['joursDebut']);
	if (isset($_GET['avant'])) $joursDebut=max($joursDebut-7, calendrier::deltaCompteurs+1);
	if (isset($_GET['apres'])) $joursDebut=$joursDebut+7;
	if (isset($_GET['now'])) $joursDebut=-2;
	$_SESSION['joursDebut']=$joursDebut;
	$joursFin=$joursDebut+13;

	if (file_exists("img/$codeTrajet/titre_charrette.gif"))
		$titreCharrette="<img src=\"img/$codeTrajet/titre_charrette.gif\">";
	else
		$titreCharrette="<div class=\"titreCharrette\">".$calendrier->trajet->titre."</div>";

	// Invitation éventuelle à consulter l'historique
	if ($joursDebut==calendrier::deltaCompteurs+1)
		$lienAvant="javascript:alert('Pour consulter les jours précédents, merci d\'utiliser le menu Information / Historique');";
	else
		$lienAvant="index.php5?avant";

	// En tête et menu
	require "inc_head_et_menu.php5";
?>
<br><br>
<center>
<?php echo $titreCharrette;?>
<br><br><br>
<?php
	//***************** Calendrier *****************
	html::htmlCalendrier($calendrier, $joursDebut, $joursFin, false, false);
?>
<table width=600>
<tr>
<td align=left nowrap><a class="lienAvantApres" href="<?php echo $lienAvant;?>">Avant</a></td>
<td align=center nowrap width=500><a class="lienAvantApres" href="index.php5?now">Aujourd'hui</a></td>
<td align=right nowrap><a class="lienAvantApres" href="index.php5?apres">Après</a></td>
</tr>
<tr>
<td align=center colspan=3 nowrap><a class="lienLegende" href="javascript:afficherMasquerLegende();">Afficher/masquer la légende</a></td>
</tr>
</table>

<?php require "inc_legende.php5";?>
<br><br>
<?php
	//***************** Récapitulatif jour suivant *****************
	html::htmlMessage($calendrier, 450);
?>
<br><br>
<?php
if (isset($trajetLie)) 
{
	//***************** Charrette liée *****************
	echo "<hr width=80%>";
	echo "<div class=\"titreCharrette\">Charrette liée : \"".$trajetLie->titre."\"</div><br>";
	html::htmlCalendrier($calendrierLie, $joursDebut, $joursFin, false, false);
	echo "<br>";
	echo "<a href=\"choisirCharrette_trt.php5?trajet=$trajetLie->codeTrajet\">Accéder à cette charrette</a>";
	echo "<br><br>";
}
?>
<br><br>
<!-- ***************** Forum ***************** -->
<table class="tableMessageForum" width=550>
<tr>
<td align=center>
	<b><u>Les 5 derniers messages du forum</u></b>
	<br><br><br><br>
	<TABLE width=90% align=center cellpadding=4 cellspacing=0 border=0>
	<?php
	if (isset($trajetLie)) $sqlTrajetLie="OR trajet='$trajetLie->codeTrajet'"; else $sqlTrajetLie="";
	$result = mysql_query("SELECT * FROM messagesforum WHERE (trajet='$codeTrajet' $sqlTrajetLie) AND supprime<>'O' ORDER BY id DESC LIMIT 5");
	while($row = mysql_fetch_array($result))
	{
		$id=$row['id'];
		$texte=$row['texte'];
		$trigramme=$row['trigramme'];
		$personne=$calendrier->getPersonne($trigramme);
		if ($personne!=null)
		{
			$prenom=$personne->Prenom();
			$nomPrenom=$personne->PrenomNom();
		}
		else
		{
			$prenom=$trigramme;
			$nomPrenom="";
		}
		$dateMsg=date_fr($row['dateMsg']);
		$texte=htmlspecialchars($texte);
		$texte=emoticone($texte);
		$texte=nl2br($texte);
		if ($texte=="") $texte="&nbsp;";
		echo "<tr><td align=left class=\"tdMessageForum\"><b title='$nomPrenom' style='color:#031947;'>$prenom</b>, le $dateMsg</td><tr>\n";
		echo "<tr valign=top><td align=left width=80%>$texte</td></tr>\n";
		echo "<tr><td><br><br></td><tr>\n";
	}
	?>
	</table>
	<br>
	<a href="forum.php5">Accéder au forum</a>
	<br><br><br>
</tr>
</td>
</table>

</center>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<?php
	//echo "<pre>";print_r($calendrier->calendrier);echo "</pre>";
	require "inc_foot.php5";
?>