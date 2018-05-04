<?php 
include 'session_start.php';
include '../Scripts/test_session.php';

include '../Scripts/global.php';

	//Traitement des données
	if(!empty($_POST)){
		$categorie = $_POST['categorie'];
		$sql = $db->prepare("UPDATE `Suggestions` SET categorie=? WHERE ID=? ");
		$sql->execute(array($_POST['categorie'],$_POST['id']));
		
		header('Location: Suggestion.php');
	}
		
?>

<html>
<HEAD>
	<title>Traiter la Suggestion</title>
</HEAD>
<body>
	
	<div id="header_bg"></div>

<?php

include 'header.php';

?>

<div id="wrapper">

<div id="content">
		<h1>Suggestion en attente</h1>
	<table>
	
	
	<?php
			if(!empty($_GET['id'])){
				$sql = $db->prepare("SELECT * FROM Suggestions WHERE ID=?");
				$sql->execute(array($_GET['id']));
				$suggestions = $sql->fetchAll();
				foreach($suggestions as $suggestion){
					echo ('<tr>');
					echo ('<td>ID de la suggestion :</td>');
					echo ('<td> '.$suggestion['ID'].'</td>');
					echo ('</tr>');
					
					echo ('<tr>');
					echo ('<td>Utilisateur : </td>');
					echo ('<td>'.$suggestion['Username'].'</td>');
					echo ('</tr>');
					
					echo ('<tr>');
					echo ('<td>Date :</td>');
					echo ('<td>'.$suggestion['CDate'].'</td>');
					echo ('</tr>');
					
					echo ('<tr>');
					echo ('<td>Message : </td>');
					echo ('<td class="message">'.$suggestion['Message'].'</td>');
					echo ('</tr>');
				?>
				
				<td>Choix :</td>
			
				<form action="traiter_suggestion.php" method="post">
				
				<?php echo ('<input hidden name="id" value="'.$suggestion['ID'].'">'); ?>

					<td>

						<select name="categorie">
							<option value="1">A faire</option>
							<option value="2">Fait</option>
							<option value="3">Poubelle</option>
						</select>
					</td>
					
					</tr>
					<tr><td><input type="submit" value="Envoyer"></td></tr>
				</form>	
			
			<?php		
				}//end of foreach
			}//end of if
			
		?>
	</table>
	</div>
</div>
</body>

</html>
