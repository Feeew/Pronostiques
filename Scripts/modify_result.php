<?php
include '../Pages/session_start.php';

include 'global.php';

$match_id = $_POST["match_id"];
$score1 = $_POST["score1"];
$score2 = $_POST["score2"];
$phase = $_POST["phase"];


$requete = "UPDATE Matchs SET score1 = $score1, score2 = $score2, phase = '".$phase."' WHERE ID = $match_id";

$update = $db->prepare($requete);
$update->execute();

echo $requete."\n";

echo $update->rowCount();

?>