<?php
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header ("content-type:text/calendar");
	require_once "inc_connexion.php5";
?>
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
X-WR-CALNAME:La Charrette
<?php
	$trigramme=$_GET["trigramme"];
	$today="";
	$result = mysql_query("SELECT DISTINCT jour FROM calendrier WHERE code in ('C', 'V') and TO_DAYS(NOW()) - TO_DAYS(jour) < 30 and trigramme='".$trigramme."'");
	while($row = mysql_fetch_array($result))
	{
		$jour=$row['jour'];
		$jour=str_replace("-", "", $jour);		// suppression des "-" dans la chaine
		$today=date("d-m-Y", mktime(0, 0, 0));
		echo "BEGIN:VEVENT\n";
		echo "DTSTART:$jour\n";
		echo "SUMMARY:Cond. charrette\n";
		echo "DESCRIPTION: MAJ : $today\n";
		echo "END:VEVENT\n";
	}
?>
END:VCALENDAR