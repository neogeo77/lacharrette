<script language="JavaScript">
function afficherMasquerLegende()
{
	table=document.getElementById("divLegende");
	if (table.style.display=="none") table.style.display="block"; else table.style.display="none";
}
</script>
<div id="divLegende" style="display:none">
<table align=center class="tableLegende">
<tr><td align=center bgcolor="<?php echo couleurJourHTMLFutur('P');?>">P</td><td align=left>Passager</td></tr>
<tr><td align=center bgcolor="<?php echo couleurJourHTMLFutur('C');?>">C</td><td align=left>Conducteur</td></tr>
<tr><td align=center bgcolor="<?php echo couleurJourHTMLFutur('O');?>">O</td><td align=left>Convenance (présent mais hors charrette)</td></tr>
<tr><td align=center bgcolor="<?php echo couleurJourHTMLFutur('A');?>">A</td><td align=left>Absent</td></tr>
<tr><td align=center bgcolor="<?php echo couleurJourHTMLFutur('V');?>">V</td><td align=left>Voiture nécessaire (conducteur imposé)</td></tr>
<tr><td align=center bgcolor="<?php echo couleurJourHTMLFutur('I');?>">I</td><td align=left>Voiture indisponible (conducteur interdit)</td></tr>
</table>
</div>
