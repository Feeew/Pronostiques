<?php
include '../Pages/session_start.php';

include 'global.php';

$tournoi_id = $_POST["tournoi_id"];
$user_id = $_POST["user_id"];
$message = $_POST["message"];
$date = time();

$requete = "INSERT INTO Messagerie (idTournoi, idUser, message, date) VALUES (:idTournoi, :idUser, :message, :date)";

$insert = $db->prepare($requete);
$insert->execute(array(
		'idTournoi'	=> $tournoi_id,
		'idUser'	=> $user_id,
		'message'	=> $message,
		'date'	=> $date
	));
	
$requete = $db->prepare("SELECT u.Username as Username, m.date as date, m.message as message from Messagerie m
			INNER JOIN Users u ON u.ID = m.idUser ORDER BY m.id DESC LIMIT 1");

$requete->execute();
			
date_default_timezone_set('Europe/Paris');
			
$last_message = $requete->fetch(); 
$last_message["date"] = date("Y-m-d H:i:s", $last_message["date"]);

echo json_encode($last_message);
	
?>