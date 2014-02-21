<?php
session_start();
?>

<html>
<head>
	<LINK href="stylesheet.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php

include 'global.php';

?>

<div id="wrapper">

<?php
	include '../Pages/header.php';
?>

<div id="content">
<?php 
if(!isset($_SESSION['connected']) || $_SESSION['connected'] == false){
	header('Location: index.php');
}
else{
	$tournoi_id = $_POST["tournoi_id"];
	$tournoi_nom = $_POST["tournoi_nom"];
	
	echo "<h1>".str_replace('_', ' ', $tournoi_nom)."</h1>";

	$stmt = $db->prepare("SELECT Users.Username as Joueur, Tournoi.Nom as Tournoi, Match.Equipe1, Match.Equipe2, Pronostic.Score1, Pronostic.Score2 FROM Users, Match, Pronostic, Tournoi WHERE Users.ID = Pronostic.ID_User AND Match.ID = Pronostic.ID_Match AND Match.ID_Tournoi = Pronostic.ID_Tournoi AND Match.ID_Tournoi = Tournoi.ID AND Tournoi.ID = ".$tournoi_id);
	$stmt->execute();
	$result = $stmt->fetchAll();
	
	$i = 0;
	
	$pronostics = array();
	
	foreach($result as $row){
		$pronostics[$i] = $row;
		$i++;
	}

	?>
		<br/>
		<table id="tournoi_pronostic">
			<tr><th>Joueur</th><th>Equipe 1</th><th>Equipe 2</th><th>Score 1</th><th>Score 2</th></tr>
		
		<?php 
			for($i = 0; $i<count($pronostics); $i++){
				echo "<tr><td>".$pronostics[$i]["Joueur"]."</td><td>".$pronostics[$i]["Equipe1"]."</td><td>".$pronostics[$i]["Equipe2"]."</td><td>".$pronostics[$i]["Score1"]."</td><td>".$pronostics[$i]["Score2"]."</td></tr>";
			}		
			echo "<br/><br/>";
	}
?>
	</table>

<br /><br /><a href='../Pages/index.php'>Retour</a>

</div>
</div>

<div id="footer"></div>

</body>
</html>