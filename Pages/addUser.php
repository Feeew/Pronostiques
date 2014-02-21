<?php
session_start();
?>
<html>
<head>
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
				<div class="input-group addUser">
				  <span class="input-group-addon" onclick="document.getElementById('username').focus();"><div class="glyphicon glyphicon-user"></div></span>
				  <input type="text" id="username" tabindex=1 class="form-control" name="username" placeholder="Nom de compte" required>
				</div>
				<div class="input-group addUser">
				  <span onclick="document.getElementById('password').focus();" class="input-group-addon"><div class="glyphicon glyphicon-lock"></div></span>
				  <input tabindex=2 id="password" type="password" name="password" class="form-control" placeholder="Mot de passe" required>
				</div>
			</div>
			<button tabindex=3 type="submit" class="btn btn-default">S'inscrire</button>
		  </form>

	<?php
}


?>
</div>
</div>
<div id="footer">
</div>
</body>
</html>