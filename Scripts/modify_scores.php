<?php
include '../Pages/session_start.php';

include 'global.php';

$user_id = $_POST["user_id"];
$tournoi_id = $_POST["tournoi_id"];

foreach($_POST["scores"] as $score){
	$myArray = explode("_", $score);
	$match_id = $myArray[0];
	$score1 = $myArray[1];
	$score2 = $myArray[2];

	if(is_numeric($score1) != 1) $score1=null;
	if(is_numeric($score2) != 1) $score2=null;

	$requete = "UPDATE Pronostic SET Score1 = $score1, Score2 = $score2 WHERE ID_Tournoi = $tournoi_id AND ID_User = $user_id AND ID_Match = $match_id";

	$update = $db->prepare($requete);
	$update->execute();

	echo $requete."\n";

	echo $update->rowCount();

}