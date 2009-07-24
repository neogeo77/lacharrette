<?php
	require "inc_commun.php5";
	require "inc_loginNecessaire.php5";
	
	// En tête et menu
	require "inc_head_et_menu.php5";


	$txtTexte = $_POST['txtTexte'];
	$_SESSION['texteForum']=$txtTexte;

	$txtTexte=htmlspecialchars($txtTexte);
	$txtTexte=emoticone($txtTexte);
	$txtTexte=nl2br($txtTexte);
	$dateMsg = date("d/m/Y à H:i");
?>
<br><br><br><br>
<TABLE align=center width=70% class="tableMessageForum">
<tr>
<td>
	<br><br>
	<center><b style="font-size:14px;">FORUM</b>
	<br><br><br><br>
	<b>Merci de confirmer votre envoi</b>
	</center>
	<br><br><br><br>
	<TABLE width=85% align=center cellpadding=4 cellspacing=0 border=0>
	<tr><td class="tdMessageForum"><b><?php echo $utilisateur->PrenomNOM();?></b>, le <?php echo $dateMsg;?></td><tr>
	<tr valign=top><td align=left width=80%><?php echo stripslashes($txtTexte);?></td></tr>
	</TABLE>
	<br><br><br><br>
	<center>
	<input type=button style="FONT-WEIGHT:bold;" value="Envoyer ce message" onClick="javascript:window.location='forum_trt.php5'">
	&nbsp;&nbsp;
	<input type=button value="Modifier ce message" onClick="javascript:window.location='forum.php5?modif=oui#ajouter'">
	<br><br><br><br>
</td>
</tr>
</table>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<?php
	require "inc_foot.php5";
?>