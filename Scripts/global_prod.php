<?php

// Afficher les erreurs &agrave; l'&eacute;cran
ini_set('display_errors', 1);
// Enregistrer les erreurs dans un fichier de log
ini_set('log_errors', 1);
// Nom du fichier qui enregistre les logs (attention aux droits &agrave; l'&eacute;criture)
ini_set('error_log', dirname(__file__) . '/logs/log_error_php.txt');

//PARAMETRES ET BDD
try{
    $dsn = 'mysql:dbname=b6_14384255_Rugby2014;host=sql300.byethost6.com';
	$user = 'b6_14384255';
	$password = 'paddle';
	try {
		$db = new PDO($dsn, $user, $password);
	} catch (PDOException $e) {
		echo "connexion echouee : " . $e->getMessage();
	}
} catch(Exception $e) {
    echo "Impossible d'acc&eacute;der &agrave; la base de donn&eacute;es SQL : ".$e->getMessage();
    die();
}
?>