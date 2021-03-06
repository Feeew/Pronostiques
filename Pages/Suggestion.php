<?php 
include 'session_start.php';
include '../Scripts/test_session.php';

include '../Scripts/global.php';

$ajout = 0;

if(isset($_POST['message']))
{
	if($_POST['message'] != null && $_POST['message'] != ""){
			$sql = $db->prepare("INSERT INTO Suggestions (Username, Message, CDate) VALUES (:username, :message, :CDate)");
			$result = $sql->execute(array(
				'username'	=> $_SESSION['username'],
				'message'	=> $_POST['message'],
				'CDate' => date('Y-m-d H:m:s')
			));
			$ajout = 1;
	}
}

?>
<html>
<HEAD>
	<title>Suggestion</title>
</HEAD>
<body>

<div id="header_bg"></div>

<?php

include 'header.php';

?>

<div id="wrapper">

<div id="content">
	<h1>Suggestion</h1>
<br />
<span class="suggest_explain">Pour toute suggestion sur le site, les graphismes, de nouvelles fonctionnalit&eacute;s ou un rapport de bug, n'h&eacutesitez pas &agrave; utiliser ce formulaire !</span>
	<br />
	<br />
	<form method="post">
		<input type="hidden" value="<?php echo $_SESSION['username'];?>" name="username" />
		<label for="message">Message : <br /><textarea rows='4' cols='40' name="message"></textarea></label>
		<br />
		<input type="submit" value="Soumettre" />
	</form>

	<?php
		if($ajout == 1){
			echo "<h4>Votre suggestion a &eacute;t&eacute; transmise !</h4>";
		}
		if($_SESSION['grade'] == 2){
	?>
	
	<h3>Liste des suggestions a faire</h3>
	<table id="suggestions">
		<tr><th>Utilisateur</th><th>Date</th><th colspan="2">Suggestion</th></tr>
		<?php
			$sql = $db->prepare("SELECT * FROM Suggestions where categorie=1 ORDER BY CDate DESC");
			$sql->execute();
			$suggestions = $sql->fetchAll();
			foreach($suggestions as $suggestion){
				echo ('<tr>');
				echo ('<td>'.$suggestion['Username']."</td><td>".$suggestion['CDate']."</td><td class='message'>".$suggestion['Message'].'</td><td><a href="traiter_suggestion.php?id='.$suggestion['ID'].'"><img src="img/oeil.png"></a></td></tr>');
			}
			
		?>
	</table>
	
	<h3>Liste des suggestions Faites</h3>
	<table id="suggestions">
		<tr><th>Utilisateur</th><th>Date</th><th colspan="2">Suggestion</th></tr>
		<?php
			$sql = $db->prepare("SELECT * FROM Suggestions where categorie=2 ORDER BY CDate DESC");
			$sql->execute();
			$suggestions = $sql->fetchAll();
			foreach($suggestions as $suggestion){
				echo ('<tr>');
				echo ('<td>'.$suggestion['Username']."</td><td>".$suggestion['CDate']."</td><td class='message'>".$suggestion['Message'].'</td><td><a href="traiter_suggestion.php?id='.$suggestion['ID'].'"><img src="img/oeil.png"></a></td></tr>');
			}
			
		?>
	</table>
	
	<h3>Liste des suggestions abandonn&eacute;es</h3>
	<table id="suggestions">
		<tr><th>Utilisateur</th><th>Date</th><th colspan="2">Suggestion</th></tr>
		<?php
			$sql = $db->prepare("SELECT * FROM Suggestions where categorie=3 ORDER BY CDate DESC");
			$sql->execute();
			$suggestions = $sql->fetchAll();
			foreach($suggestions as $suggestion){
				echo ('<tr>');
				echo ('<td>'.$suggestion['Username']."</td><td>".$suggestion['CDate']."</td><td class='message'>".$suggestion['Message'].'</td><td><a href="traiter_suggestion.php?id='.$suggestion['ID'].'"><img src="img/oeil.png"></a></td></tr>');
			}
			
		?>
	</table>
	
	<?php
		}
	?>
</div>
</div>

<?php
	include './footer.php';
?>

</body>
</html>
