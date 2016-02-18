<?php 
include 'session_start.php';
include '../Scripts/test_session.php';
?>
<html>
<head>
	<title>Tournoi</title>
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
else if(!isset($_POST['tournoi_id']) || !isset($_POST['tournoi_nom'])){
	header('Location: mesTournois.php');
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
		<br />
		<h4><span class="glyphicon glyphicon-info-sign" style="top:2px;"></span> Comment modifier son score : </h4>
		<p>Cliquez sur votre pseudo dans le tableau (Noir gras souligné). Les scores des matchs non-joués deviennent alors modifiables. Cliquez à nouveau sur votre pseudo pour valider les scores.</p>
		<br/>
		<h4><span class="glyphicon glyphicon-info-sign" style="top:2px;"></span> Quelques règles : </h4>
		<p>Un bon pronostique donne 3 points. Un écart de 5 points ou moins entre l'écart de votre score et l'écart du résultat final donne 2 points bonus.</p>
		<p></p>
		<p>Par exemple, si vous pronostiquez 15-11, l'écart est de 4. Si le résultat final est 17-15, l'écart est de 2. La différence des écarts est de 2 (4-2), donc vous marquez 2 points bonus, pour un total de 5 points.</p>
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
			if($_SESSION["grade"]==2) $options = "";
			date_default_timezone_set('CET');
			foreach($result_equipes as $row1){
				$score_match1 = ($row1["score1"] != null) ? $row1["score1"] : "-";
				$score_match2 = ($row1["score2"] != null) ? $row1["score2"] : "-";
				if((date("d-m-Y") == date("d-m-Y", strtotime($row1["Date"]))) && ($score_match1=="-" && $score_match2=="-"))
					echo "<tr class='yellow_line en_cours' id=".$row1["ID"].">";
				else if(strtotime(date("d-m-Y")) < strtotime($row1["Date"]))
						echo "<tr id=".$row1["ID"]." class='en_cours'>";
					 else echo "<tr id=".$row1["ID"].">";
				echo "
							<td class='unirow'>".date("d-m-Y", strtotime($row1["Date"]))."</td>
							<td class='unirow'>".$row1["Equipe1"]."</td>
							<td class='unirow'>".$row1["Equipe2"]."</td>
				";
				if($_SESSION["grade"]==2 && (date("d-m-Y") == date("d-m-Y", strtotime($row1["Date"])) || strtotime(date("d-m-Y")) > strtotime($row1["Date"]))) 
					{
						$options.="<option value='".$row1["ID"]."'>".$row1["Equipe1"]." / ".$row1["Equipe2"]."</option>";
					}
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
								$ecart_point = abs(abs($score_match2-$score_match1) - abs($score2 - $score1));
								if($ecart_point <= 5  && $ecart_point >= 0)
									$points += 2;
								$str_points="<td class='correct case_result'>".$points."</td>";
							}
							else{
								$points=0;
								$str_points="<td class='incorrect case_result'>".$points."</td>";
							}
						}
						if((strtoupper($row2["Username"]) == strtoupper($_SESSION['username'])) && $match_termine == 0) echo "<td class='result case_result ".$row2["Username"]."'>".$score1."</td><td class='result case_result ".$row2["Username"]."'>".$score2."</td>".$str_points;
						else echo "<td class='result case_result'>".$score1."</td><td class='result case_result'>".$score2."</td>".$str_points;

						if($points > 0) $tab_scores[$row2["Username"]] = $tab_scores[$row2["Username"]] + $points;
				}
				echo "<td class='result case_result'>".$score_match1."</td><td class='result case_result'>".$score_match2."</td></tr>";
			}
	}
?>
	</table>
</div>

<br /><br />
<div id="result_messagerie">
	<div class="final_results tableau" id="div_final_results" style="margin:0">
		<table id="final_results" class="tournoi_pronostic"><tr><th class='th_joueur' style="border-right:1px solid black">Joueur</th><th>Score</th></tr>
			<?php

				arsort($tab_scores);
				
				foreach($tab_scores as $key=>$value){
						echo "<tr><td>".$key."</td><td>".$value."</td></tr>";
				}
				
			?>
		</table>
	</div>
	<div class="messagerie">
		<table id="messagerie_table" class="tournoi_pronostic">
			<thead>
				<tr>
					<th colspan="99">Messagerie</th>
				</tr>
			</thead>
			<?php
				$messagerie = $db->prepare("SELECT u.Username, m.date, m.message from Messagerie m
											INNER JOIN Users u ON u.ID = m.idUser
											WHERE m.idTournoi = " .$tournoi_id. " ORDER BY m.date DESC");
				$messagerie->execute();
				$au_moins_un_message = false;
				while($message = $messagerie->fetch()){
					echo "<tr class='tr_input_message'>";
						echo "<td class='nom_message'>".date("Y-m-d h:i:s", $message["date"])."</td>";
						echo "<td class='nom_message'>".$message["Username"]."</td>";
						echo "<td class='message'>".$message["message"]."</td>";
					echo "</tr>";
					$au_moins_un_message = true;
				}
			?>
			<tfoot>
				<tr class='tr_input_message'>
					<td colspan='99'>
						<input type="text" id="message" name="message" placeholder="Votre message" style="width:93%; text-align:left; padding-left:3px;"/>
						<input type="button" id="buttonAddMessage" value="Envoyer" style="width:7%; float:right;" onclick="addMessage();" />
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
	
	
<div class="clear"></div>
</div>


<br /><br />
<?php 
if($_SESSION["grade"]==2) {
?>
<fieldset>
	<legend>Partie Admin</legend>
	<form method="post" id="form_mod_result">
		<h4>Changer le résultat d'un match</h4><br />
		<select id="mod_id">
			<?php echo $options;?>
		</select>
		<input type="number" required style="width:46px; height:27px; text-align:center;" name="score1" id="mod_score1" placeholder="-" />
		<input type="number" required style="width:46px; height:27px; text-align:center;" name="score2" id="mod_score2" placeholder="-" />
		<input type="hidden" name="tournoi_id" value="<?php echo $tournoi_id; ?>"/>
		<input type="hidden" name="tournoi_nom" value="<?php echo $tournoi_nom; ?>"/>
		<input type="button" value="Go !" onclick="modifyResult();"/>
	</form>
	<br /><br />
	<form method="post" id="form_add_match">
		<h4>Ajouter un match</h4><br />
		<table>
			<tr>
				<td><label for="Equipe1" style="margin-right:14px;" >Nom equipe 1</label></td>
				<td><input type="text" required name="Equipe1" id="Equipe1" placeholder="Equipe 1" /></td>
			</tr>
			<tr>
				<td><label for="Equipe2" style="margin-right:14px;" >Nom equipe 2</label></td>
				<td><input type="text" required name="Equipe2" id="Equipe2" placeholder="Equipe 2" /></td>
			</tr>
			<tr>
				<td><label for="DateMatch" style="margin-right:14px;" >Date du match</label></td>
				<td><input type="text" name="DateMatch" id="DateMatch" placeholder="Date du match"/></td>
			</tr>
		</table>
		<br />
		
		<input type="hidden" name="tournoi_id" value="<?php echo $tournoi_id; ?>"/>
		<input type="button" value="Go !" onclick="addMatch();"/>
	</form>
</fieldset>
<?php
}
?>



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

<script type="text/javascript">
	$("#DateMatch").datetimepicker({ dateFormat: 'yy-mm-dd' });
	
	$("#message").keyup(function(event){
		if(event.keyCode == 13){
			$("#buttonAddMessage").click();
		}
	});
</script>
	
</body>
</html>