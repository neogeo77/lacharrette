<?php
	require "inc_commun.php5";
	$_SESSION['pageCourante']=$_SERVER['PHP_SELF'];		// nom de la page courante (exemple: "master.php5") La mémoriser permet de revenir sur cette page une fois l'identification (connecterMonCompte.php5) effectuée.

	// En tête et menu
	require "inc_head_et_menu.php5";
?>
<br><br><br>
<center>
<?php

	function cheminParent($repOuFichier)
	{
		$pos=strrpos($repOuFichier, "/");
		$parent=substr($repOuFichier, 0, $pos);
		return $parent;
	}

	if (!$bEstConnecte)
	{
		echo "<br><br><span class=\"titreGalerie\">Galerie Photo</span><br><br><br><br>";
		echo "<a href=\"connecterMonCompte.php5\"><br><br>Connectez vous pour accéder à la galerie photo</a>";
	}
	else
	{
		if (isset($_GET["rep"])) $repCourant=$_GET["rep"]; else $repCourant="";
		$chemin="img/$codeTrajet/galerie/".$repCourant;
		$estRacineGalerie=($repCourant=="");

		// ********** Affiche le titre de la page, uniquement à la racine **********
		if ($estRacineGalerie)
		{
			$titre=$calendrier->trajet->titre;
			echo "<br><br><span class=\"titreGalerie\">Galerie Photo</span><br><br><br><br>";
		}

		// ********** Parcours des fichiers et répertoires du répertoire "galerie" de la charrette **********
		$repertoires=array();
		$fichiers=array();
		if (file_exists($chemin))
		{
			$dir = opendir($chemin) or die('Erreur');
			while($repOuFichier=readdir($dir))
			{
				if ($repOuFichier!='.' && $repOuFichier!='..' && $repOuFichier!="Thumbs.db")
				{
					if(is_dir($chemin.'/'.$repOuFichier))
					{
						$repertoires[]=$repOuFichier;
					}
					else
					{
						$fichiers[]=$repOuFichier;
					}
				}
			}
			closedir($dir);
			sort($fichiers);
			sort($repertoires);
		}
		else
		{
			echo "<br><br>Le répertoire est vide<br><br><br><br>";
		}

		// ********** Affiche éventuellement l'icone du répertoire parent, suivi de la liste des répertoires **********
		echo "<table class=\"tableRepertoire\">";
		if (!$estRacineGalerie)
		{
			$cheminParent=cheminParent($repCourant);
			echo "<tr>";
			echo "<td colspan=2 style=\"text-align:center;\"><a href=\"galerie.php5?rep=$cheminParent\"><img border=0 src=\"img/up.gif\"></a></td>";
			echo "</tr>";
		}

		if (sizeof($repertoires)!=0)
		{
			echo "<tr><td colspan=2>&nbsp;</td></tr>";
			foreach ($repertoires as $rep)
			{
				$repAffiche=str_replace("_", " ", $rep);
				echo "<tr>";
				echo "<td><a href=\"galerie.php5?rep=$repCourant/$rep\"><img border=0 src=\"img/folder.gif\">&nbsp;</a></td>";
				echo "<td><a href=\"galerie.php5?rep=$repCourant/$rep\">$repAffiche</a></td>";
				echo "</tr>";
			}
		}
		echo "</table>";
		echo "<br><br>";

		// ********** Affiche les images **********
		if (sizeof($fichiers)!=0)
		{
			foreach ($fichiers as $fichier)
			{
				echo "<img src=\"$chemin/$fichier\"><br><br><br><br><br><br><br><br>";
			}
		}
	}
?>

</center>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<?php
	require "inc_foot.php5";
?>
