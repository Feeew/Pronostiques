<?php 
session_start();
?>
<html>
<HEAD>
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

?>
</div>
</div>

<?php
	include './footer.php';
?>

</body>
</html>