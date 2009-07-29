<?php
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header ("content-type:text/calendar");
	require_once "inc_connexion.php5";
?>
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//Google Inc//Google Calendar 70.9054//EN
X-WR-CALNAME:La Charrette
<?php
	$trigramme=$_GET["trigramme"];
	$today="";
	$result = mysql_query("SELECT DISTINCT jour, GROUP_CONCAT(trajet) trajets FROM calendrier WHERE code in ('C', 'V') and TO_DAYS(NOW()) - TO_DAYS(jour) < 30 and trigramme='".$trigramme."' GROUP BY jour");
	while($row = mysql_fetch_array($result))
	{
		$jour=$row['jour'];
		$trajets=$row['trajets'];
		$trajet=substr ($trajets, 0, 3);
		$jour=str_replace("-", "", $jour);		// suppression des "-" dans la chaine
		$today=date("d-m-Y", mktime(0, 0, 0));
		echo "BEGIN:VEVENT\n";
		echo "DTSTART:$jour\n";
		echo "SUMMARY:Cond. charrette\n";
		echo "DESCRIPTION: MAJ : $today, TRAJETS : $trajets\n";
		echo "X-GOOGLE-CALENDAR-CONTENT-TITLE:Conducteur Charrette ($trajets)\n";
		echo "X-GOOGLE-CALENDAR-CONTENT-ICON:http://www.blueplace.fr/charrette/img/iconGoogleCalender.gif\n";
		echo "X-GOOGLE-CALENDAR-CONTENT-URL:http://www.blueplace.fr/charrette/tel.php5?CODETRAJET=$trajet\n";
		echo "X-GOOGLE-CALENDAR-CONTENT-TYPE:text/html\n";
		echo "X-GOOGLE-CALENDAR-CONTENT-WIDTH:360\n";
		echo "X-GOOGLE-CALENDAR-CONTENT-HEIGHT:350\n";
		echo "END:VEVENT\n";
	}
?>
END:VCALENDAR