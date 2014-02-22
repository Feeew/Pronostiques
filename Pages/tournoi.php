<?php 
session_start();
?>
<html>
<head>
</head>
<body>

<div id="header_bg"></div>

<?php
	include '../Scripts/global.php';

	include 'header.php';
?>

<div id="wrapper">


<div id="content">
<?php 
if(!isset($_SESSION['connected']) || $_SESSION['connected'] == false){
	header('Location: index.php');
}
else{
	$tournoi_id = $_POST["tournoi_id"];
	$tournoi_nom = $_POST["tournoi_nom"];
	
	echo "<input type='HIDDEN' id='TOURNOI_ID' value=".$tournoi_id." />";
	echo "<input type='HIDDEN' id='USER_ID' value=".$_SESSION['user_id']." />";
	
	echo "<h1>".str_replace('_', ' ', $tournoi_nom)."</h1>";

	$equipes = $db->prepare("SELECT Matchs.Equipe1, Matchs.Equipe2, Matchs.ID, Matchs.Date, Matchs.score1, Matchs.score2 from Matchs Where Matchs.ID_Tournoi = ".$tournoi_id." ORDER BY Matchs.Date");
	$equipes->execute();
	$result_equipes = $equipes->fetchAll();
		
	$joueurs = $db->prepare("SELECT Users.Username, Users.ID FROM Users, Inscriptions WHERE Inscriptions.ID_Tournoi = ".$tournoi_id." AND Users.ID = Inscriptions.ID_user ORDER BY ID");
	$joueurs->execute();
	$result_joueurs = $joueurs->fetchAll();
	
	?>
		<br/>
		<div class="CSSTableGenerator" >
		<table id="tournoi_pronostic" class='tournoi_pronostic'>
			<tr>
				<td>Date</td>
				<td>Equipe 1</td>
				<td>Equipe 2</td>
				<?php
					foreach($result_joueurs as $row){
						$tab_scores[$row["Username"]] = 0;
						if(strtoupper($row["Username"]) == strtoupper($_SESSION['username'])) echo "<td colspan='3' id=".strtoupper($row['Username']).">".$row["Username"]."<span onclick='mod_score(\"".strtoupper($row['Username'])."\")' class='glyphicon glyphicon-pencil'></span></td>";
						else echo "<td colspan='3'>".$row["Username"]."</td>";
					}
					echo "<td colspan='2'>Résultats</td>";
			?>
			</tr>
		
		<?php 
		
			foreach($result_equipes as $row1){
				if(date("d-m-Y") == date("d-m-Y", strtotime($row1["Date"])))
				echo "<tr class='yellow_line' id=".$row1["ID"].">";
				else echo "<tr id=".$row1["ID"].">";
				echo "
							<td class='unirow'>".date("d-m-Y", strtotime($row1["Date"]))."</td>
							<td class='unirow'>".$row1["Equipe1"]."</td>
							<td class='unirow'>".$row1["Equipe2"]."</td>
				";
				$score_match1 = ($row1["score1"] != null) ? $row1["score1"] : "-";
				$score_match2 = ($row1["score2"] != null) ? $row1["score2"] : "-";
				foreach($result_joueurs as $row2){
						$scores = $db->prepare("SELECT Score1, Score2 FROM Pronostic WHERE ID_Tournoi = ".$tournoi_id." AND ID_user = ".$row2["ID"]." AND ID_Match = ".$row1["ID"]." ORDER BY ID_User");
						$scores->execute();
						$result_scores = $scores->fetchAll();
						$match_termine = ($row1["Date"]<date("Y-m-d H:i:s", strtotime("+2 hour"))) ? "1" : "0";
						$score1 = $result_scores[0]["Score1"];
						$score2 = $result_scores[0]["Score2"];
						$points = "-";
						$str_points = "<td class='points'>".$points."</td>";
						if($match_termine==1 && ($score1!="-" || $score2!="-")){
							if((($score_match1-$score_match2)>0 && ($score1-$score2)>0) || (($score_match2-$score_match1)>0 && ($score2-$score1)>0)) 
							{
								$points = 3;
								$str_points="<td class='correct'>".$points."</td>";
							}
							else{
								$points=0;
								$str_points="<td class='incorrect'>".$points."</td>";
							}
						}
						if((strtoupper($row2["Username"]) == strtoupper($_SESSION['username'])) && $match_termine == 0) echo "<td class='result ".$row2["Username"]."'>".$score1."</td><td class='result ".$row2["Username"]."'>".$score2."</td>".$str_points;
						else echo "<td class='result'>".$score1."</td><td class='result'>".$score2."</td>".$str_points;

						if($points==3) $tab_scores[$row2["Username"]] = $tab_scores[$row2["Username"]] + $points;
				}
				echo "<td class='result'>".$score_match1."</td><td class='result'>".$score_match2."</td></tr>";
			}
	}
?>
	</table>
</div>

<br /><br />

<div class="CSSTableGenerator final_results">
<table id="final_results"><tr><td>Joueur</td><td>Score</td></tr>
<?php

	asort($tab_scores);
	
	foreach($tab_scores as $key=>$value){
			echo "<tr><td>".$key."</td><td>".$value."</td></tr>";
	}
	
?>
</table>
</div>

<br /><br />
<fieldset class="hidden">
	<legend>Rapport d'activité</legend>
	<div id="bugs"></div>
</fieldset>
<input class="hidden" type="button" value="Vider" onclick="document.getElementById('bugs').innerHTML=''" />
</div>
</div>




<?php
	include './footer.php';
?>
	
</body>
</html>