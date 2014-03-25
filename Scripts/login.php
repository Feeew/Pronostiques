<?php
include '../Pages/session_start.php';

include '../Scripts/global.php';

if(isset($_SESSION['connected'])){
	if($_SESSION['connected'] == true){
		header('Location: ../Pages/index.php');
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
			$sql = "SELECT ID FROM Users WHERE USERNAME = '".strtoupper($username)."'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll();
			$_SESSION['connected'] = true;
			$_SESSION['username'] = $username;
			$_SESSION['user_id'] = $result[0]["ID"];

			/*ECRITURE DANS LA TABLE DE LOG*/
			$sql = $db->prepare("INSERT INTO Logs (login, IP, Connect_date) VALUES (:username, :ip, :CDate)");
			$result = $sql->execute(array(
				'username'	=> $username,
				'ip'	=> $_SERVER['REMOTE_ADDR'],
				'CDate' => date('Y-m-d H:m:s')
			));
			header('Location: ../Pages/index.php');
		}
		else header('Location: ../Pages/index.php?err=1');;
		
	}
	catch(Exception $e){
		header('Location: ../Pages/index.php?err=3');
	}
}
else
{
		header('Location: ../Pages/index.php?err=2');
}