<?php

// Afficher les erreurs à l'écran
ini_set('display_errors', 1);
// Enregistrer les erreurs dans un fichier de log
ini_set('log_errors', 1);
// Nom du fichier qui enregistre les logs (attention aux droits d'écriture)
ini_set('error_log', dirname(__file__) . '/logs/log_error_php.txt');

//PARAMETRES ET BDD
try{
    $dsn = 'mysql:dbname=pronostiques;host=localhost';
	$user = 'root';
	$password = '';
	try {
		$db = new PDO($dsn, $user, $password);
	} catch (PDOException $e) {
		echo "connexion echouee : " . $e->getMessage();
	}
} catch(Exception $e) {
    echo "Impossible d'accéder à la base de données SQL : ".$e->getMessage();
    die();
}
?>