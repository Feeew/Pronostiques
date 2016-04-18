<?php
include 'session_start.php';
include '../Scripts/test_session.php';

include '../Scripts/global.php';
?>
<html>
<head>
	<title>Inscription &agrave; un tournoi</title>
</head>
<body>


<?php
	include 'header.php';
?>

<div id="wrapper">

<div id="content">

<h1>Inscription &agrave; un tournoi</h1></br>
<?php

if (!isset($_POST['tournoi_id']))
{	
	$user_id = $_SESSION["user_id"];
    $sql = $db->prepare("SELECT * FROM Tournoi WHERE ID NOT IN (Select ID_Tournoi From Inscriptions WHERE ID_User = $user_id)");
	$sql->execute();
	$result = $sql->fetchAll();
	$tournois = array();
	$i = 0;
	
	foreach($result as $row){
		$tournois[$i] = $row;
		$i++;
	}
	
	if(count($tournois) == 0){
		echo "<h4>Aucun tournoi n'est disponible pour le moment.</h4>";
	}
	else{
		echo "<br /><h2>Tournois disponibles : </h2>";
		echo "<form method='post' action='".$_SERVER['PHP_SELF']."' id='all_tournois_form'>";
		echo "<table id='inscription_tournoi'>";
		for($i = 0; $i < count($tournois); $i++){
			$sport = (($tournois[$i]['Sport'] == "foot") ? "Football" : (($tournois[$i]['Sport'] == "rugby") ? "Rugby" : ""));
			echo "
				<tr>
					<td>
						<div class='row'>
						  <div class='col-lg-6'>
							<div class='input-group'>
							  <span class='input-group-btn'>
									<button onclick=submit_inscription_tournoi(".$tournois[$i]["ID"].") class='btn btn-default' type='button'>S'inscrire</button>
							  </span>
							  <span class='tournoi_name' onclick='submit_inscription_tournoi(\"".$tournois[$i]['ID']."\")'> " . $tournois[$i]['Nom']."<span style='float:right'> - ".$sport."</span></span>
							  
							</div>
						  </div>
						</div>
					</td>
				</tr>
			";
		}
		echo "</table>";
		echo "<input type='hidden' value='' name='tournoi_id' id='tournoi_id'/>";
		echo "</form>";
	}
	
}else
{ 
	$user_id = $_SESSION["user_id"];
	$tournoi_id = $_POST["tournoi_id"];
	
	try{
		$sql = $db->prepare("INSERT INTO Inscriptions (ID_Tournoi, ID_User) VALUES (:tournoi_id, :user_id)");
		$result = $sql->execute(array(
			'tournoi_id'	=> $tournoi_id,
			'user_id'	=> $user_id
		));
		$sql = $db->prepare("SELECT ID FROM Matchs WHERE ID_Tournoi = ".$tournoi_id);
		$sql->execute();
		$result = $sql->fetchAll();
		foreach($result as $row){
			$sql = $db->prepare("INSERT INTO Pronostic (ID_User, ID_Tournoi, ID_Match, Score1, Score2) VALUES (:user_id, :tournoi_id, :match_id, 0, 0)");
			$result = $sql->execute(array(
				'user_id'	=> $user_id,
				'tournoi_id'	=> $tournoi_id,
				'match_id'	=> $row["ID"]
			));
		}
		echo "<b>Inscription termin&eacute;e. Bonne chance !</b>"; 
		echo "<br />";
		echo "<a href='mesTournois.php'>Retour &agrave; la liste de mes tournois</a>";
	}
	catch(Exception $e){
		echo "Erreur dans l'inscription au tournoi : ".$e->getMessage();
	}
}


?>
</div>
</div>



<?php
	include './footer.php';
?>

</body>
</html>