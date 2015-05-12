<?php
include 'session_start.php';
?>
<html>
<head>
	<title>Inscription</title>
</head>
<body>

<?php

include '../Scripts/global.php';

if(isset($_SESSION['connected'])){
	if($_SESSION['connected'] == true){
		header('Location: index.php');
	}
}

	include 'header.php';
?>

<div id="wrapper">


<div id="content">

<h1>S'inscrire</h1></br>
<?php

if (isset($_POST['password']) && isset($_POST['username'])) 
{ 
	$username = $_POST["username"];
	
	$magic_key = "0c1e2u3m4k5a6d7v8l9m10d11p12";
	$password = strtoupper(md5(sha1($_POST["password"]).$magic_key));
	
	try{
		$sql = $db->prepare("INSERT INTO Users (username, password) VALUES (:username, :password)");
		$result = $sql->execute(array(
			'username'	=> strtoupper($username),
			'password'	=> strtoupper($password)
		));
		echo "Ajout réussi! Bienvenu, $username.";
	}
	catch(Exception $e){
		echo "Erreur dans l'ajout du compte : ".$e->getMessage();
	}
}
else
{
    ?>

    	<form method="post" action="addUser.php" class="">
			<div class="form-group">
				<div class="input-group formAjout">
				  <span class="input-group-addon" onclick="document.getElementById('username').focus();"><div class="glyphicon glyphicon-user"></div></span>
				  <input type="text" id="username" tabindex=3 class="form-control" name="username" placeholder="Nom de compte" required>
				</div>
				<div class="input-group formAjout">
				  <span onclick="document.getElementById('password').focus();" class="input-group-addon"><div class="glyphicon glyphicon-lock"></div></span>
				  <input tabindex=4 id="password" type="password" name="password" class="form-control" placeholder="Mot de passe" required>
				</div>
			</div>
			<button tabindex=5 type="submit" class="btn btn-default">S'inscrire</button>
		</form>

		  <h5 style='color:red;'>
		  		Mes connaissances en cyber-sécurité étant limitées, je ne garantis pas la complète sécurité de vos identifiants ou mot de passe. 
		  		<br />
		  		Je vous conseille donc fortement d'utiliser un mot de passe différent de ceux dont vous avez l'habitude.
		  </h5>

	<?php
}


?>
</div>
</div>
<div id="footer">
</div>
</body>
</html>