<?php 

include 'session_start.php';

include '../Scripts/test_session.php';

?>

<html>

<head>

	<title>Tournoi</title>

	<meta charset="utf-8" />

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

	$sport = $_POST["tournoi_sport"];

	

	echo "<input type='HIDDEN' id='TOURNOI_ID' value=".$tournoi_id." />";

	echo "<input type='HIDDEN' id='USER_ID' value=".$_SESSION['user_id']." />";

	

	

	echo "<h1>".str_replace('_', ' ', $tournoi_nom)." - " . $sport . "</h1>";



	$equipes = $db->prepare("SELECT Matchs.Equipe1, Matchs.Equipe2, Matchs.ID, Matchs.Date, Matchs.score1, Matchs.score2, Matchs.phase from Matchs Where Matchs.ID_Tournoi = ".$tournoi_id." ORDER BY Matchs.Date");

	$equipes->execute();

	$result_equipes = $equipes->fetchAll();

		

	$joueurs = $db->prepare("SELECT Users.Username, Users.ID FROM Users, Inscriptions WHERE Inscriptions.ID_Tournoi = ".$tournoi_id." AND Users.ID = Inscriptions.ID_user ORDER BY ID");

	$joueurs->execute();

	$result_joueurs = $joueurs->fetchAll();

	

	?>

		<br />

		<h4><span class="glyphicon glyphicon-info-sign" style="top:2px;"></span> Comment modifier son score : </h4>

		<p>Cliquez sur votre pseudo dans le tableau (Noir gras soulign&eacute; Les scores des matchs non-jou&eacute;s deviennent alors modifiables. Cliquez &agrave; nouveau sur votre pseudo pour valider les scores.</p>

		<br/>

		<h4><span class="glyphicon glyphicon-info-sign" style="top:2px;"></span> Quelques r&egrave;gles : </h4>

		<?php 

			switch($sport){

				case "Rugby" : ?>

					<p>Un bon pronostique (le bon vainqueur) donne 3 points. Un &eacute;cart de 5 points ou moins entre l'&eacute;cart de votre score et l'&eacute;cart du r&eacute;sultat final donne 2 points bonus.</p>

					<p></p>

					<p>Par exemple, si vous pronostiquez 15-11, l'&eacute;cart est de 4. Si le r&eacute;sultat final est 17-15, l'&eacute;cart est de 2. La diff&eacute;rence des &eacute;carts est de 2 (4-2), donc vous marquez 2 points bonus, pour un total de 5 points.</p>

				<?php

					break;

				case "Football" : 

					?>

					<p>Un bon pronostique donne 3 points. Le score exact donne 2 points bonus (total de 5 points).</p>
					<p>Ajout de phase du tournoi et d'un coefficient multiplicateur pour les phases finales : </p> 
					<div style="background-color: #5A3A22; height: 20px; width: 20px; display: inline-block;"></div> * 2 lors des 8éme de finale
					<div style="background-color: #cd7f32; height: 20px; width: 20px; display: inline-block;"></div>
					* 3 lors des quart de finale
					<div style="background-color: #059af4; height: 20px; width: 20px; display: inline-block;"></div>
					* 4 lors des demis finale
					<div style="background-color: #ffd700; height: 20px; width: 20px; display: inline-block;"></div>
					* 5 pour la finale.
					</p>

					<?php

					break;

				default : echo "C'est pas un sport, contactez l'administrateur qui a certainement fait une c*nnerie."; break;

			}

			?>

			

			<br />

			<p>(Laissez votre curseur sur la date du match pour afficher l'heure)</p>

			

		<br/>

		<div class="Tableau">

		<table id="tournoi_pronostic" class='tournoi_pronostic'>

			<tr>

				<th class='th_date sticky-header'>Date</th>

				<th class='th_team sticky-header'>Equipe 1</th>

				<th class='th_team sticky-header'>Equipe 2</th>

				<?php

					foreach($result_joueurs as $row){

						$tab_scores[$row["Username"]] = 0;

						if(strtoupper($row["Username"]) == strtoupper($_SESSION['username'])) 

							echo "<th colspan='3' class='th_joueur sticky-header' id='".$row['Username']."' style='font-weight:bold; color:black; text-decoration:underline;' onclick='mod_score(\"".strtoupper($row['Username'])."\")'>".substr($row["Username"], 0, 5)."</th>";

						else echo "<th class='th_joueur sticky-header' colspan='3'>".substr($row["Username"], 0, 5)."</th>";

					}
					echo "<th colspan='2' class='th_resultat sticky-header'>R&eacute;sultats</th>";

			?>

			</tr>

		

		<?php 

			if($_SESSION["grade"]==2) $options = "";

			date_default_timezone_set('CET');

			//Boucle qui parcourt tous les matchs
			foreach($result_equipes as $row1){

				$score_match1 = ($row1["score1"] != null) ? $row1["score1"] : "-";

				$score_match2 = ($row1["score2"] != null) ? $row1["score2"] : "-";

				$phase = ($row1["phase"] != null) ? $row1["phase"] : "";

				if((date("d-m-Y") == date("d-m-Y", strtotime($row1["Date"]))) && ($score_match1=="-" && $score_match2=="-"))

					echo "<tr class='yellow_line en_cours' id=".$row1["ID"].">";

				else if((strtotime(date("d-m-Y")) < strtotime($row1["Date"]))&&($phase == "none"))
						echo "<tr id=".$row1["ID"]." class='en_cours'>";
				else if((strtotime(date("d-m-Y")) >= strtotime($row1["Date"]))&&($phase == "8eme"))
						echo "<tr id=".$row1["ID"]." class='choco_line'>";
				else if((strtotime(date("d-m-Y")) >= strtotime($row1["Date"]))&&($phase == "4eme"))
						echo "<tr id=".$row1["ID"]." class='bronze_line'>";
				else if((strtotime(date("d-m-Y")) >= strtotime($row1["Date"]))&&($phase == "2eme"))
						echo "<tr id=".$row1["ID"]." class='silver_line'>";
				else if((strtotime(date("d-m-Y")) >= strtotime($row1["Date"]))&&($phase == "1eme"))
						echo "<tr id=".$row1["ID"]." class='gold_line'>";

				else echo "<tr id=".$row1["ID"].">";

				echo "

							<td class='unirow' title='".$row1["Date"]."'><span style='border-bottom:1px dotted black;'>".date("d-m-y H:i", strtotime($row1["Date"]))."</span></td>

							<td class='unirow'>".$row1["Equipe1"]."</td>

							<td class='unirow'>".$row1["Equipe2"]."</td>

				";

				if($_SESSION["grade"]==2 && (date("d-m-Y") == date("d-m-Y", strtotime($row1["Date"])) || strtotime(date("d-m-Y")) > strtotime($row1["Date"]))) 

					{

						$options.="<option value='".$row1["ID"]."'>".$row1["Equipe1"]." / ".$row1["Equipe2"]."</option>";

					}

				//Boucle qui parcourt tous les joueurs pour chaque match

				foreach($result_joueurs as $row2){

						$scores = $db->prepare("SELECT Score1, Score2 FROM Pronostic WHERE ID_Tournoi = ".$tournoi_id." AND ID_user = ".$row2["ID"]." AND ID_Match = ".$row1["ID"]." ORDER BY ID_User");

						$scores->execute();

						$result_scores = $scores->fetchAll();

						$match_termine = ($row1["Date"]<date("Y-m-d H:i:s", strtotime("+1 hour"))) ? "1" : "0";

						$score1 = $result_scores[0]["Score1"];

						$score2 = $result_scores[0]["Score2"];

						$points = "";

						$str_points = "<td class='points case_result'>".$points."</td>";


						//Si le match est terminé et que les scores ont été renseigné

						if($match_termine==1 && $score_match1 != "-" && $score_match2 != "-"){

							//Si les scores sont bons

							if(

								(

									(

										($score_match1-$score_match2)>0 && ($score1-$score2)>0

									) 

									|| 

									(

										($score_match2-$score_match1)>0 && ($score2-$score1)>0

									)

									||

									(

										($score_match1 == $score_match2) && ($score1 == $score2)

									)

								) && $score1 != null && $score2 != null

							) 

							{

								$points = 3;

								if($sport == "Rugby"){

									$ecart_point = abs(abs($score_match2-$score_match1) - abs($score2 - $score1));

									//Si l'écart de point est bon

									if($ecart_point <= 5  && $ecart_point >= 0)

										$points += 2;

								}

								else if($sport == "Football"){

									//Si le score est exact

									if($score1 == $score_match1 && $score2 == $score_match2)
										$points += 2;
									if($phase == "8eme")
										$points = $points * 2;
									else if($phase == "4eme")
										$points = $points * 3;
									else if($phase == "2eme")
										$points = $points * 4;
									else if($phase == "1eme")
										$points = $points * 5;

								}

								
								$str_points="<td class='correct case_result'>".$points."</td>";

							}
							//Si les scores ne sont pas bons

							else{

								$points=0;

								$str_points="<td class='incorrect case_result'>".$points."</td>";

							}

						}



// || ($_SESSION['username'] == "Akiah" && $sport == "Rugby")

						

						//Si l'utilisateur connecté est celui dont on affiche les résultats

						if(strtoupper($row2["Username"]) == strtoupper($_SESSION['username']) && $match_termine == 0)

							echo "<td class='result case_result ".$row2["Username"]."'>".$score1."</td><td class='result case_result ".$row2["Username"]."'>".$score2."</td>".$str_points;

						else if($match_termine == 1)

							echo "<td class='result case_result'>".$score1."</td><td class='result case_result'>".$score2."</td>".$str_points;

						else{

							if(is_null($score1) && is_null($score2))

								echo "<td class='result case_result'>x</td><td class='result case_result'>x</td>".$str_points;

							else

								echo "<td class='result case_result'></td><td class='result case_result'></td>".$str_points;

						}

							



						if($points > 0) $tab_scores[$row2["Username"]] = $tab_scores[$row2["Username"]] + $points;

				}

				echo "<td class='result case_result score_color'>".$score_match1."</td><td class='result case_result score_color'>".$score_match2."</td></tr>";

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

				$messagerie = $db->prepare("SELECT * from Messagerie m

											INNER JOIN Users u ON u.ID = m.idUser

											WHERE m.idTournoi = " .$tournoi_id. " ORDER BY m.date DESC");

				$messagerie->execute();

				$au_moins_un_message = false;

				while($message = $messagerie->fetch()){
					$modif_msg = none;
					if ($message["Username"] == strtoupper ($_SESSION['username'] )) {
						$modif_msg = block;
					}

					echo "<tr id='".$message["id"]."' class='tr_input_message'>";

						echo "<td class='nom_message'>".date("Y-m-d H:i:s", $message["date"])."</td>";

						echo "<td class='nom_message'>".$message["Username"]."</td>";

						echo "<td class='message'>".$message["message"]."</td>";

						echo "<td> <button style ='display:".$modif_msg."'; id='".$message["id"]."' type='button' onclick='modifMessage(".$message["id"].")'>Modifier</button> </td>";

					echo "</tr>";

					$au_moins_un_message = true;

				}

			?>

			<tfoot>

				<tr class='tr_input_message'>

					<td colspan='99'>

						<input type="text" id="message" name="message" placeholder="Votre message" style="width:90%; text-align:left; padding-left:3px;"/>

						<input type="button" id="buttonAddMessage" value="Envoyer" style="width:10%; float:right;" onclick="addMessage();" />

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

		<h4>Changer le r&eacute;sultat d'un match</h4><br />

		<select id="mod_id">

			<?php echo $options;?>

		</select>

		<input type="number" required style="width:46px; height:27px; text-align:center;" name="score1" id="mod_score1" placeholder="-" />

		<input type="number" required style="width:46px; height:27px; text-align:center;" name="score2" id="mod_score2" placeholder="-" />

		<select name="phase" id="phase">
			<option value="none" selected>Phase de poules</option> 
		  	<option value="8eme">Huitièmes de finale * 2</option> 
		  	<option value="4eme">Quarts de finale * 3</option>
		  	<option value="2eme">Demis finale * 4 </option>
		  	<option value="1eme">Finale * 5</option>
		</select>

		<input type="hidden" name="tournoi_sport" value='<?php echo $sport; ?>'/>

		<input type="hidden" name="tournoi_id" value="<?php echo $tournoi_id; ?>"/>

		<input type="hidden" name="tournoi_nom" value="<?php echo $tournoi_nom; ?>"/>

		<input type="button" value="Go !" onclick="modifyResult();"/>

	</form>

	<br /><br />

	<div id="msg_ajout"></div>
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

	<legend>Rapport d'activit&eacute;</legend>

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

<script>
$('.message').click(function(){
    $this = $(this)
    $this.replaceWith( $('<textarea id="text_change" style="width:100%; height: 100%;" />').val( $this.text() ) )
})

function modifMessage(id) {
    var text = $("#text_change").val();
    var id =  id;
    console.log(text);
    console.log(id);
    $.ajax({
        url: "ajax_msg.php", 
        type: "POST",
		dataType: "json",
		data: {text: text, id: id},      
     })
    $("#text_change").replaceWith( $('<td class= "message" style="width:100%; height: 100%;" />').text( text) )
}

</script>	

</body>

</html>