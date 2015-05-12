<?php
include '../Pages/session_start.php';

include 'global.php';

$tournoi_id = $_POST["tournoi_id"];
$equipe1 = $_POST["equipe1"];
$equipe2 = $_POST["equipe2"];
$dateMatch = $_POST["dateMatch"];

$requete = "INSERT INTO Matchs (Equipe1, Equipe2, ID_Tournoi, Date) VALUES (:equipe1, :equipe2, :id_tournoi, :date)";

$insert = $db->prepare($requete);
$insert->execute(array(
		'equipe1'	=> $equipe1,
		'equipe2'	=> $equipe2,
		'id_tournoi'	=> $tournoi_id,
		'date'	=> $dateMatch
	));

$id_match = $db->lastInsertId();

$membresTournoi = $db->prepare("SELECT ID_User FROM Inscriptions WHERE ID_Tournoi = :id_tournoi");
$membresTournoi->execute(array(
	'id_tournoi' => $tournoi_id
));
$result_membres = $membresTournoi->fetchAll();

foreach($result_membres as $membre){
	$sql = $db->prepare("INSERT INTO Pronostic (ID_User, ID_Tournoi, ID_Match, Score1, Score2) VALUES (:user_id, :tournoi_id, :match_id, 0, 0)");
	$result = $sql->execute(array(
		'user_id'	=> $membre['ID_User'],
		'tournoi_id'	=> $tournoi_id,
		'match_id'	=> $id_match
	));
}

?>