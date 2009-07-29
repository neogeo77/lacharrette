<?php
	require "inc_commun.php5";
	require "inc_loginNecessaire.php5";
	
	// En tête et menu
	require "inc_head_et_menu.php5";

	if (isset($_GET['modif'])) $modif=$_GET['modif']; else $modif="";
	if ($modif=="oui")
	{
		$txtTexte = $_SESSION['texteForum'];
		$txtTexte = stripslashes($txtTexte);
	}
	else
	{
		$txtTexte = "";
	}
?>
<br><br><br><br><br>
<!--<A href="#ajouter">Ajouter un message</A>-->
<TABLE align=center width=70% class="tableMessageForum">
<tr>
<td>
<br>
<center><img src="img/forum.gif"></center>
<br><br><br>
<TABLE width=85% align=center cellpadding=4 cellspacing=0 border=0>
<?php
if (isset($_GET["allmsg"])) 
  $limit="";
else
  $limit=" LIMIT 20";
if (isset($trajetLie)) $sqlTrajetLie="OR trajet='$trajetLie->codeTrajet'"; else $sqlTrajetLie="";
$result = mysql_query("SELECT * FROM messagesforum WHERE (trajet='$codeTrajet' $sqlTrajetLie) AND supprime<>'O' ORDER BY id DESC".$limit);
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
	echo "<tr><td class=\"tdMessageForum\"><b title='$nomPrenom' style='color:#031947;'>$prenom</b>, le $dateMsg</td><tr>\n";
	echo "<tr valign=top><td align=left width=80%>$texte</td></tr>\n";
	echo "<tr><td><br><br><br></td><tr>\n";
}
?>
<tr>
<td>
	<br>
	<A name="ajouter"></A>
	<br><br>
	<hr>
	<b>Ajouter un message</b>
	<hr>
	<script language="JavaScript">
	function valider()
	{
	  if(document.formSaisie.txtTexte.value == "")
	  {
		alert("Saisissez du texte !");
		return false;
	  }
	  return true;
	}

	function affichage_popup(nom_de_la_page, nom_interne_de_la_fenetre)
	{
	window.open (nom_de_la_page, nom_interne_de_la_fenetre, config='height=320, width=170, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no')
	}
	</script>
	<form method=POST action="forumConfirme.php5" onsubmit="return valider()" name="formSaisie">
	<table align=center>
	<tr>
	<td><textarea rows=10 cols=70 name="txtTexte"><?php echo $txtTexte;?></textarea><br>
	Utilisez les <A HREF="javascript:affichage_popup('emoticones.html', 'popup_1');">émoticones !</A> <img src="img/emoticones/3.gif">
	<br><br><br><br>
	<center>
	<input type=submit value="Envoyer">
	</center>
	</td>
	</tr>
	</table>
	</form>
</td>
</tr>
</table>

</td>
</tr>
</table>


<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<?php
	require "inc_foot.php5";
?>