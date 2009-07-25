<?php

// ********************************************************
// Configuration de la connexion à la base de données
// ********************************************************
$host="127.0.0.1";
$login="root";
$password="";
$base="charretteDB";

// ********************************************************
$conn = mysql_connect($host, $login, $password);
if (!$conn)
{
    echo "Unable to connect to DB: " . mysql_error();
    exit;
}
if (!mysql_select_db($base, $conn))
{
    echo "Unable to select database: " . mysql_error();
    exit;
}

function executeSql($sql)
{
	//echo $sql."<br><br>"; // die();  // décommenter pour débugguer
	if (!mysql_query($sql))
	{
		mysql_query("ROLLBACK");
		die('Erreur SQL !'.$sql.'<br>'.mysql_error());
	}
}

function executeSqlErreur($sql)
{
	mysql_query($sql);
	$err=mysql_errno();
	switch($err)
	{
		case 0:
	    	$ret="OK";
	    	break; 
		case 1062:
	    	$ret="CLE_EN_DOUBLE";
	    	break; 
		default:
	    	$ret="ERREUR_".$err;
	    	break;
	}
	return $ret;
}

//
// Ajoute les caractères d'échapement pour MySql (en supprimant l'opération effectuée par le magic_quotes)
//
function mysqlEscape($value)
{
    if (get_magic_quotes_gpc())
    {
        $value = stripslashes($value);
    }
    $value = mysql_real_escape_string($value);
    return $value;
}
?>