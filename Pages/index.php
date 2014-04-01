<?php 
include 'session_start.php';
?>
<html>
<HEAD>
	<title>Accueil</title>
</HEAD>
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
	echo "<h1>Bienvenue !</h1>";
}
else{
	?><h1>Bonjour <?php echo $_SESSION["username"]; ?> !</h1><?php
}

if(isset($_GET["err"])){
	$erreur = $_GET["err"];
	switch($erreur){
		case "1": echo "<h4 style='color:red;'>Erreur de connexion : L'utilisateur n'existe pas ou le mot de passe est erronn&eacute;.</h4>";break;
		case "2": echo "<h4 style='color:red;'>Merci de renseigner tous les champs.</h4>";break;
		case "3": echo "<h4 style='color:red;'>Une erreur s'est produite, merci de contacter l'administrateur.</h4>";break;
		case "4": echo "<h4 style='color:red;'>Votre session a expir&eacute;, merci de vous reconnecter.</h4>";break;
		default: break;
	}	
}

?>
<br />
<h4>Derniers ajouts</h4>
<h5>V1.4 - 1 Avril 2014</h5>
<p>Ajout d'une partie Administration sur la page des pronostics d'un tournoi permettant &agrave; un administrateur de modifier le r&eacute;sultat d'un match.</p>
<p>Ajout d'un message d'alerte lors de l'inscription à propos de la cyber-sécurité.</p>

<br />
<h5>V1.3 - 25 Mars 2014</h5>
<p>Ajout du formulaire de suggestion dans l'onglet "Suggestion" afin de permettre aux utilisateurs de proposer des am&eacute;liorations, ajouts, corrections.</p>
<p>Ajout de la page "Contact" listant les diff&eacute;rents moyens de me contacter.</p>
<p>Ajout de messages d'erreurs sur la page d'accueil lors d'une erreur &agrave; l'authentification ou lorsque la session a expir&eacute;.</p>

<h4>Prochains ajouts</h4>
<p>Différencier les sports selon les tournois (Rugby, Foot...).</p>
<p>Attribuer des points bonus en fonction du score (pronostic exact pour le Foot, X points d'écarts pour le rugby).</p>

</div>
</div>

<?php
	include './footer.php';
?>

</body>
</html>