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
	
	//Ancienne fonction avec l'icone "crayon" pour modifier
	//if(strtoupper($row["Username"]) == strtoupper($_SESSION['username']) || 1==1) echo "<td colspan='3' id=".strtoupper($row['Username'])."><span class='th_edit'>".$row["Username"]."</span><span onclick='mod_score(\"".strtoupper($row['Username'])."\")' style='float:right;' class='glyphicon glyphicon-pencil'></span></td>";
		
	?>
		<br />
		<h4><span class="glyphicon glyphicon-info-sign" style="top:2px;"></span> Comment modifier son score : </h4>
		<p>Cliquez sur votre pseudo dans le tableau (Noir gras souligné). Les scores des matchs non-joués deviennent alors modifiables. Cliquez à nouveau sur votre pseudo pour valider les scores.</p>
		<br/>
		<div class="Tableau">
		<table id="tournoi_pronostic" class='tournoi_pronostic'>
			<tr>
				<th class='th_date'>Date</th>
				<th class='th_team'>Equipe 1</th>
				<th class='th_team'>Equipe 2</th>
				<?php
					foreach($result_joueurs as $row){
						$tab_scores[$row["Username"]] = 0;
						if(strtoupper($row["Username"]) == strtoupper($_SESSION['username'])) 
							echo "<th colspan='3' class='th_joueur' id='".$row['Username']."' style='font-weight:bold; color:black; text-decoration:underline;' onclick='mod_score(\"".strtoupper($row['Username'])."\")'>".$row["Username"]."</th>";
						else echo "<th class='th_joueur' colspan='3'>".$row["Username"]."</th>";
					}
					echo "<th colspan='2' class='th_resultat'>Résultats</th>";
			?>
			</tr>
		
		<?php 
		
			date_default_timezone_set('CET');
			foreach($result_equipes as $row1){
				$score_match1 = ($row1["score1"] != null) ? $row1["score1"] : "-";
				$score_match2 = ($row1["score2"] != null) ? $row1["score2"] : "-";
				if((date("d-m-Y") == date("d-m-Y", strtotime($row1["Date"]))) && ($score_match1!="-" && $score_match2!="-"))
				echo "<tr class='yellow_line' id=".$row1["ID"].">";
				else echo "<tr id=".$row1["ID"].">";
				echo "
							<td class='unirow'>".date("d-m-Y", strtotime($row1["Date"]))."</td>
							<td class='unirow'>".$row1["Equipe1"]."</td>
							<td class='unirow'>".$row1["Equipe2"]."</td>
				";
				foreach($result_joueurs as $row2){
						$scores = $db->prepare("SELECT Score1, Score2 FROM Pronostic WHERE ID_Tournoi = ".$tournoi_id." AND ID_user = ".$row2["ID"]." AND ID_Match = ".$row1["ID"]." ORDER BY ID_User");
						$scores->execute();
						$result_scores = $scores->fetchAll();
						$match_termine = ($row1["Date"]<date("Y-m-d H:i:s", strtotime("+1 hour"))) ? "1" : "0";
						$score1 = $result_scores[0]["Score1"];
						$score2 = $result_scores[0]["Score2"];
						$points = "-";
						$str_points = "<td class='points case_result'>".$points."</td>";
						if($match_termine==1 && ($score1!="-" || $score2!="-")){
							if((($score_match1-$score_match2)>0 && ($score1-$score2)>0) || (($score_match2-$score_match1)>0 && ($score2-$score1)>0)) 
							{
								$points = 3;
								$str_points="<td class='correct case_result'>".$points."</td>";
							}
							else{
								$points=0;
								$str_points="<td class='incorrect case_result'>".$points."</td>";
							}
						}
						if((strtoupper($row2["Username"]) == strtoupper($_SESSION['username'])) && $match_termine == 0) echo "<td class='result case_result ".$row2["Username"]."'>".$score1."</td><td class='result case_result ".$row2["Username"]."'>".$score2."</td>".$str_points;
						else echo "<td class='result case_result'>".$score1."</td><td class='result case_result'>".$score2."</td>".$str_points;

						if($points==3) $tab_scores[$row2["Username"]] = $tab_scores[$row2["Username"]] + $points;
				}
				echo "<td class='result case_result'>".$score_match1."</td><td class='result case_result'>".$score_match2."</td></tr>";
			}
	}
?>
	</table>
</div>

<br /><br />

<div class="final_results tableau" style="margin:0">
<table id="final_results" class="tournoi_pronostic"><tr><th class='th_joueur' style="border-right:1px solid black">Joueur</th><th>Score</th></tr>
<?php

	arsort($tab_scores);
	
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