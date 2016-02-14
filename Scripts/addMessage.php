<?php
include '../Pages/session_start.php';

include 'global.php';

$tournoi_id = $_POST["tournoi_id"];
$user_id = $_POST["user_id"];
$message = $_POST["message"];

$requete = "INSERT INTO Messagerie (idTournoi, idUser, message) VALUES (:idTournoi, :idUser, :message)";

$insert = $db->prepare($requete);
$insert->execute(array(
		'idTournoi'	=> $tournoi_id,
		'idUser'	=> $user_id,
		'message'	=> $message
	));
	
$requete = $db->prepare("SELECT u.Username as Username, m.date as date from Messagerie m
			INNER JOIN Users u ON u.ID = m.idUser ORDER BY m.id DESC LIMIT 1");

$requete->execute();
			
$last_message = $requete->fetch(); 

echo json_encode($last_message);
	
?>