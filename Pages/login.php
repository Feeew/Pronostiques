<?php
session_start();

include '../Scripts/global.php';

if(isset($_SESSION['connected'])){
	if($_SESSION['connected'] == true){
		header('Location: index.php');
	}
}



if (isset($_POST['password']) && isset($_POST['username']))
{ 
	$username = $_POST["username"];
	
	
	$magic_key = "0c1e2u3m4k5a6d7v8l9m10d11p12";
	$password = strtoupper(md5(sha1($_POST["password"]).$magic_key));


	try{
	
		$sql = "SELECT count(*) as exist FROM Users WHERE upper(username) = upper('$username') AND upper(password) = upper('$password')";
		
		$stmt = $db->prepare($sql);
		$stmt->execute();
		$result = $stmt->fetchAll();
		
		$exist = $result[0]['exist'];
		
		if($exist){
			$sql = "SELECT ID FROM USERS WHERE USERNAME = '".strtoupper($username)."'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll();
			$_SESSION['connected'] = true;
			$_SESSION['username'] = $username;
			$_SESSION['user_id'] = $result[0]["ID"];
			header('Location: index.php');
		}
		else header('Location: index.php?err=1');;
		
	}
	catch(Exception $e){
		header('Location: index.php?err=3');
	}
}
else
{
		header('Location: index.php?err=2');
}