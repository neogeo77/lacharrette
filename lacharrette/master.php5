<?php
	require "inc_commun.php5";
	require "inc_loginNecessaire.php5";
	require_once "class.html.php5";

	if ($utilisateur->estAncien)
	{
		header('Location: pageInterditeAuxAnciens.php5');
		exit;
	}

	// Tri
	if (isset($_GET['dateTri']))
	{
		$datePourTri=intval($_GET['dateTri']);
		$_SESSION['dateTri']=$datePourTri;
	}
	elseif (isset($_SESSION['dateTri']))
	{
		$datePourTri=$_SESSION['dateTri'];
	}
	else
	{
		$datePourTri=$calendrier->aujourdhui();
	}
	$jourTravailleSuivant=$calendrier->jourTravailleSuivant($datePourTri);
    $personnes=$calendrier->getPersonnesTrieesPourMaster($datePourTri);

	// Nombre de jours avant et après
	if (!isset($_SESSION['joursDebutMaster'])) $_SESSION['joursDebutMaster']="-2";
	$joursDebut=intval($_SESSION['joursDebutMaster']);
	if (isset($_GET['avant'])) $joursDebut=max($joursDebut-7, calendrier::deltaCompteurs+1);
	if (isset($_GET['apres7'])) $joursDebut=$joursDebut+7;
	if (isset($_GET['apres21'])) $joursDebut=$joursDebut+21;
	if (isset($_GET['now'])) $joursDebut=-2;
	$_SESSION['joursDebutMaster']=$joursDebut;
	$joursFin=$joursDebut+16;
   	$_SESSION['joursAvant']=$joursDebut;	// ne sert que pour la page suivante : master_trt.php5
   	$_SESSION['joursApres']=$joursFin;		// ne sert que pour la page suivante : master_trt.php5

	// Lien "avant"
	if ($joursDebut==calendrier::deltaCompteurs+1)
	{
		$lienAvant="javascript:alert('Il n\'est pas possible d\'effectuer de modifications avant cette date');";
	}
	else
	{
		$lienAvant="master.php5?avant";
	}

	// Message du charrette master
	$conducteurs=$calendrier->getConducteurs($jourTravailleSuivant);
	$passagers=$calendrier->getPassagers($jourTravailleSuivant);
	$conducteursDefaut="";
	$sep="";
	foreach ($conducteurs as $pers) {$conducteursDefaut=$conducteursDefaut.$sep.$pers->trigramme;$sep=", ";}
	$passagersDefaut="";
	$sep="";
	foreach ($passagers as $pers) {$passagersDefaut=$passagersDefaut.$sep.$pers->trigramme;$sep=", ";}
	$texteMsgDefaut=$calendrier->trajet->texteMsgDefaut;
	$texteMsgDefaut=str_replace("\"", "\\\"", $texteMsgDefaut);
	$texteMsgDefaut=str_replace(chr(13), "\\n", $texteMsgDefaut);
	$texteMsgDefaut=str_replace(chr(10), "", $texteMsgDefaut);
	$texteMsgDefaut=str_replace("#PASS", $passagersDefaut, $texteMsgDefaut);
	$texteMsgDefaut=str_replace("#COND", $conducteursDefaut, $texteMsgDefaut);
	$message=message::chargerPourDate($codeTrajet, $jourTravailleSuivant);
	if ($message==NULL) $message=new message($codeTrajet, "", "O", $jourTravailleSuivant);
	$boolDefaut=($message->boolDefaut=="O");	// indique si le message par défaut (conducteurs/passagers) doit être affiché ou pas
	$_SESSION['MESSAGECM']=$message;

	// En tête et menu
	require "inc_head_et_menu.php5";
?>
<script language="JavaScript">

sauvColorFond="";
sauvColorEcriture="";

function messageParDefaut()
{
	document.getElementById("txtMessage").value="<?php echo $texteMsgDefaut;?>";
}

function clickTrigramme(aTD)
{
	trigramme=aTD.firstChild.data;	// le firstChild est le texte contenu dans la cellule TD
	window.location="absenceMaster.php5?trigramme="+trigramme;
}

function clickPouC(aTD, quand)
{

	document.getElementById("btnEnregistrer").disabled=false;
	document.getElementById("btnAnnuler").disabled=false;
	if (aTD.firstChild.data=="C")
	{
		nouvelleValeur="P";
		if (quand=="futur") sCouleur="<?php echo couleurFutur("P");?>";
		if (quand=="passe") sCouleur="<?php echo couleurPasse("P");?>";
	}
	else
	{
		nouvelleValeur="C";
		if (quand=="futur") sCouleur="<?php echo couleurFutur("C");?>";
		if (quand=="passe") sCouleur="<?php echo couleurPasse("C");?>";
	}
	aTD.firstChild.data=nouvelleValeur;	// le firstChild est le texte contenu dans la cellule TD
	aTD.lastChild.value=nouvelleValeur;	// le lastChild est le "input type=hidden"
	aTD.style.backgroundColor=sCouleur;
	sauvColor=sCouleur;
}
function mouseoverTD(aTD)
{
	sauvColorEcriture=aTD.style.color;
	aTD.style.color="#FF0000";
	sauvColor=aTD.style.backgroundColor;
	aTD.style.backgroundColor="#FFFFFF";
}

function mouseoutTD(aTD)
{
	aTD.style.color=sauvColorEcriture;
	aTD.style.backgroundColor=sauvColor;
}

function montrerMessage()
{
	div=document.getElementById("divMessage");
	if (div.style.display=="none") div.style.display="block"; else div.style.display="none";
}
</script>
<center>
<br><br><h1>CHARRETTE MASTER</h1>
<i>Tableau trié selon les ratios <?php echo strtolower($calendrier->deLibelleSimple($datePourTri));?> pour la charrette <b><?php echo strtolower($calendrier->deLibelleSimple($jourTravailleSuivant));?></b></i>
<br><br>
<form method="POST" action="master_trt.php5">
<?php
//***************** Ligne des titres *****************
echo '<table class="tableCalendrier">';
echo "<tr class=\"hautTable\">";
echo "<td>Pers.</td>";
echo "<td>Pl.</td>";
for ($jour=$joursDebut;$jour<$joursFin;$jour++)
{
	$dt_jour = calendrier::aujourdhuiPlus($jour);
	if ($calendrier->estWeekEndOuFerie($dt_jour)) $classe="cellWeekEnd"; else $classe = "cellSemaine";
	if ($datePourTri==$dt_jour) $style='style="color:#DD0000;"'; else $style='';
	echo "<td align=center nowrap class=\"$classe\">";
	echo "<a $style href=\"master.php5?dateTri=$dt_jour\">";
	echo ucfirst(nomDuJourAbrege($dt_jour)).'<br>'.date("d", $dt_jour).'<br>'.nomDuMoisAbrege(date("n", $dt_jour));
	echo "</a>";
	echo '</td>';
}
echo "<td align=center>Ratios au<br><font color=#DD0000>".nomDuJourAbrege($datePourTri)." ".date("d", $datePourTri)."</font></td>";
echo "<td align=center>Cond.<br><font color=#DD0000>".nomDuJourAbrege($datePourTri)." ".date("d", $datePourTri)."</font></td>";
echo "<td align=center>Pass.<br><font color=#DD0000>".nomDuJourAbrege($datePourTri)." ".date("d", $datePourTri)."</font></td>";
echo "</tr>";

//***************** Lignes des personnes *****************
foreach ($personnes as $personne)
{
	$trigramme=$personne->trigramme;
	$nbrePlaces=$personne->nbrePlaces;
	$nbreP=$calendrier->nbrePPourDate($trigramme, $datePourTri);
	$nbreC=$calendrier->nbreCPourDate($trigramme, $datePourTri);
	$ratio=$calendrier->ratioPourDate($trigramme, $datePourTri);
	$ratio=round($ratio, 5);
	if ($ratio==0) $ratio="0.0";
	$ratio=substr($ratio."00000", 0, 7);
	echo "<tr>";
	echo "<td onmouseover=\"mouseoverTD(this);\" onmouseout=\"mouseoutTD(this);\" style=\"cursor:pointer;\" onclick=\"clickTrigramme(this);\" class=\"gaucheTable\">$trigramme</td>";
	echo "<td class=\"gaucheTable\">$nbrePlaces</td>";
	for ($jour=$joursDebut;$jour<$joursFin;$jour++)
	{
		$date = calendrier::aujourdhuiPlus($jour);
		if ($calendrier->estWeekEndOuFerie($date))
		{
			echo '<td align=center class="cellWeekEnd">';
			echo '</td>';
		}
		else
		{
			$code = $calendrier->getCode($date, $trigramme);
			$couleurFond = couleurJourHTML($code, $date);
			if ($date<time()) $couleurEcriture="#888888"; else $couleurEcriture="#000000";
			if (($code=='A') || ($code=='V') || ($code=='I') || ($code=='O'))
			{
				echo "<td align=center style=\"color:$couleurEcriture;\" bgcolor=\"$couleurFond\">";
				echo $code;
				echo '</td>';
			}
			else
			{
				$nomHTML = nomHTML('td', $trigramme, $date);
				$quand = ($date>=time()?"futur":"passe");
				echo "<td onmouseover=\"mouseoverTD(this);\" onmouseout=\"mouseoutTD(this);\" style=\"cursor:pointer;\" onclick=\"clickPouC(this, '$quand');\" id=\"$nomHTML\" align=center style=\"color:$couleurEcriture;\" bgcolor=\"$couleurFond\">";
				echo $code;
				$nomHTML = nomHTML('hid', $trigramme, $date);
				echo "<input type=hidden name=\"$nomHTML\" value=\"$code\">";
				echo '</td>';
			}
		}
	}
	echo "<td align=center class=\"gaucheTable\">$ratio</td>";
	echo "<td align=center class=\"gaucheTable\">$nbreC</td>";
	echo "<td align=center class=\"gaucheTable\">$nbreP</td>";
	echo "</tr>\n";
}

//***************** Ligne des "Nombre de personne par jour" *****************
echo "<tr class=\"hautTable\">";
echo "<td colspan=2>Total</td>";
for ($jour=$joursDebut;$jour<$joursFin;$jour++)
{
	$date = calendrier::aujourdhuiPlus($jour);
	if ($calendrier->estWeekEndOuFerie($date))
	{
		echo '<td align=center class="cellWeekEnd">';
		echo '</td>';
	}
	else
	{
		
		$nbre = $calendrier->nbrePersonnePourJour($date);
		echo "<td align=center>$nbre</td>";
	}
}
echo "<td>&nbsp;</td>";
echo "<td>&nbsp;</td>";
echo "<td>&nbsp;</td>";
echo "</tr>\n";

echo "</table>\n";
?>
<center>

<table width=600>
<tr>
<td align=left nowrap><a class="lienAvantApres" href="<?php echo $lienAvant;?>">Avant</a></td>
<td align=center nowrap width=500><a class="lienAvantApres" href="master.php5?now">Aujourd'hui</a></td>
<td align=right nowrap><a class="lienAvantApres" href="master.php5?apres7">Après-1sem</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="lienAvantApres" href="master.php5?apres21">Après-3sem</a></td>
</tr>
<tr>
<td align=center colspan=3 nowrap><a class="lienLegende" href="javascript:afficherMasquerLegende();">Afficher/masquer la légende</a></td>
</tr>
</table>

<?php require "inc_legende.php5";?>
<br><br>
<input type="submit" id="btnEnregistrer" disabled value="Enregistrer">&nbsp;&nbsp;&nbsp;<input id="btnAnnuler" disabled type="button" onclick="window.location='master.php5';" value="Annuler">
<br><br>
</form>
</center>

<?php
if (isset($trajetLie)) 
{
	//***************** Charrette liée *****************
	//echo "<br><br><hr width=80%>";
	echo "<br><b>Charrette liée : \"".$trajetLie->titre."\" (lecture seule)</b><br><br>";
	html::htmlCalendrier($calendrierLie, $joursDebut, $joursFin, false, true);
	echo "<br>";
	echo "<a href=\"choisirCharretteMaster_trt.php5?trajet=$trajetLie->codeTrajet\">Accéder à la page 'Charrette Master' de cette Charrette</a>";
	echo "<br><br><br><br>";
}
?>

<table width=70%>
<tr><td align=left>
<b>Autre</b><br>
<a href="javascript:montrerMessage();">Ajouter un message pour <?php echo strtolower($calendrier->libelleSimple($jourTravailleSuivant));?> (lié à la date du tri)</a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if (trim($message->texte)<>"") echo "<span style=\"FONT-SIZE:9px;\"><- un message est présent</span>";?><br>
<div id="divMessage" style="display:none">
<form method="POST" action="masterMessage_trt.php5">
<ul>
<textarea style="FONT-SIZE:11px;" cols=70 rows=15 name="txtMessage" id="txtMessage">
<?php echo stripslashes($message->texte);?>
</textarea><br>
<input <?php if ($boolDefaut) echo "checked "; ?> name="boolDefaut" type="checkbox">Afficher le message par défaut (cond/pass)
<br><br>
<input type="submit" value="Enregistrer">&nbsp;&nbsp;&nbsp;<input type="button" onclick="window.location='master.php5';" value="Annuler">&nbsp;&nbsp;&nbsp;<input type="button" onclick="messageParDefaut()" value="Pré-remplir">
</ul>
</form>
</div>
<a href="gererMembres.php5">Gérer les membres / les infos persos / les compteurs</a><br>
<a href="gererJoursFeries.php5">Gérer les jours fériés</a><br>
<br>
<b>Instructions / documentation</b><br>
- Cliquer sur les cases à modifier, puis enregistrer.<br>
- Seuls les 'P' et 'C' peuvent être modifiés directement ici.<br>
- Les 'A', 'O', 'V', et 'I' peuvent être modifiés en cliquant sur le trigramme.<br>
- La date <span style="color:#DD0000;">en rouge</span> est la date à laquelle le tri par ratio est effectué.<br>
- Pour changer le tri, cliquer sur la date souhaitée. Cela influe aussi sur la date du message.<br>
- Les personnes en "I" (voiture interdite) sont placées en bas de tableau par convention (pour faciliter le travail du CM)<br>
</td>
</tr>
</table>
</center>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<?php
	require "inc_foot.php5";
?>