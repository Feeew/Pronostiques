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
</div>
</div>

<?php
	include './footer.php';
?>

</body>
</html>