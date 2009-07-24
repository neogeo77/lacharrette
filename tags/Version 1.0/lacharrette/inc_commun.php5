<?php
	/********************************************************************************/
	/* Tous les mécanismes de connexions et de gestion des variables de session se trouvent ici.
	/* Toute la cette complexité est nécessaire.
	/*********************************************************************************/
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	require_once "class.calendrier.php5";
	require_once "class.calendrierHistorique.php5";
	require_once "class.personne.php5";
	require_once "class.message.php5";
	require_once "inc_fonctions.php5";
	session_start();

	if (!isset($_SESSION["CODETRAJET"]))
	{
		if (isset($_GET["CODETRAJET"]))
		{
			$codeTrajet= $_GET["CODETRAJET"];
			$_SESSION["CODETRAJET"]=$codeTrajet;
		}
		elseif (isset($_COOKIE["CODETRAJET"]) && ($_COOKIE["CODETRAJET"]!=""))
		{
			$codeTrajet= $_COOKIE["CODETRAJET"];
			$_SESSION["CODETRAJET"]=$codeTrajet;
		}
		else
		{
			header('Location: choisirCharrette.php5');
			exit;
		}
	}
	$codeTrajet=$_SESSION['CODETRAJET'];

	if (strpos($_SERVER['PHP_SELF'], "index.php5") || !isset($_SESSION['CALENDRIER']))
	{
		// Sur la page d'accueil (index.php5), on force le rechargement du calendrier
		$calendrier = new calendrier();
		$calendrier->charger($codeTrajet);
		$_SESSION['CALENDRIER']=$calendrier;

		// Cas des charrettes liées
		$codeTrajetLie=$calendrier->trajet->trajetLie;
		if ($codeTrajetLie<>null) 
		{
			$calendrierLie = new calendrier();
			$calendrierLie->charger($codeTrajetLie);
			$_SESSION['CALENDRIER_LIE']=$calendrierLie;
		}
		else
		{
			unset($_SESSION['CALENDRIER_LIE']);
		}
	}

	if (isset($_SESSION['CALENDRIER_LIE']))
	{
		$calendrierLie=$_SESSION['CALENDRIER_LIE'];
		$trajetLie = $calendrierLie->trajet;
	}

	if (!isset($_SESSION['CALENDRIER']))
	{
		header('Location: index.php5');
		exit;
	}
   	$calendrier=$_SESSION['CALENDRIER'];
	
	if (!isset($_SESSION['UTILISATEUR']))
	{
		if (isset($_COOKIE["utilisateur"]) && ($_COOKIE["utilisateur"]!=""))
		{
			$trigrammeCook = $_COOKIE["utilisateur"];
			$utilisateurCook = $calendrier->getPersonne($trigrammeCook);
			if ($utilisateurCook==null)
			{
				// L'utilisateur trouvé dans le cookie ne fait pas partie de la charrette -> déconnexion (s'il était connecté)
				unset($_SESSION['UTILISATEUR']);
			}
			else
			{
				$_SESSION['UTILISATEUR']=$utilisateurCook;
			}
		}
	}
	if (isset($_SESSION['UTILISATEUR']))
	{
		$bEstConnecte=true;
		$utilisateur=$_SESSION['UTILISATEUR'];
		$trigrammeUser=$utilisateur->trigramme;
	}
	else
	{
		$bEstConnecte=false;
	}
?>