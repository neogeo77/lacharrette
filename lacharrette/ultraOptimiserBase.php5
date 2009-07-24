<?php
	require "inc_commun.php5";
	require "inc_ultraCharretteMasterNecessaire.php5";

	// En tête et menu
	require "inc_head_et_menu.php5";
?>
<br><br><h1>OPTIMISER LA BASE</h1><br>
<br><br><br><br>

<center>

INSERT INTO calendrierhistorique
SELECT * FROM calendrier WHERE TO_DAYS(NOW()) - TO_DAYS(jour) > 60;<br><br>
DELETE FROM calendrier WHERE TO_DAYS(NOW()) - TO_DAYS(jour) > 60;<br><br>
OPTIMIZE TABLE  `calendrier`;<br><br>
</center>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php
	require "inc_foot.php5";
?>