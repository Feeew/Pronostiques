<?php
include 'global.php';

$requete = "INSERT INTO Messagerie (idUser, idTournoi, date, message) VALUES (:idUser, :idTournoi, :date, :message)";

$insert = $db->prepare($requete);
$insert->execute(array(
		'idUser'	=> 1,
		'idTournoi'	=> 2,
		'date'	=> date("Y-m-d"),
		'message'	=> "test"
	));
?>
