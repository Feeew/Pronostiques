<?php
include '../Pages/session_start.php';
include 'global.php';

require_once('PHPMailer-master/PHPMailerAutoload.php');

define('GUSER', 'seillier.benjamin@gmail.com'); // GMail username
define('GPWD', 'paddle62'); // GMail password

function smtpmailer($to, $from, $from_name, $subject, $body) { 
	global $error;
	$mail = new PHPMailer();  // create a new object
	$mail->IsSMTP(); // enable SMTP
	$mail->SMTPDebug = 1;  // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true;  // authentication enabled
	$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 465; 
	$mail->Username = GUSER;  
	$mail->Password = GPWD;           
	$mail->SetFrom($from, $from_name);
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->IsHTML(true);  
	$mail->AddAddress($to);
	if(!$mail->Send()) {
		$error = 'Mail error: '.$mail->ErrorInfo; 
		return false;
	} else {
		$error = 'Message sent!';
		return true;
	}
}

$no_prono = $db->prepare("SELECT 
							Users.Username, Users.Email, 
							Matchs.Equipe1, Matchs.Equipe2, Matchs.Date, Matchs.score1 as m1, Matchs.score2 as m2,
							Pronostic.Score1, Pronostic.Score2 
						FROM 
							Users 
						INNER JOIN 	Inscriptions ON Users.ID = Inscriptions.ID_User 
						INNER JOIN Tournoi ON Inscriptions.ID_Tournoi = Tournoi.ID 
						INNER JOIN Matchs ON Matchs.ID_Tournoi = Tournoi.ID 
						INNER JOIN Pronostic ON Pronostic.ID_Match = Matchs.ID AND Pronostic.ID_Tournoi = Tournoi.ID AND Pronostic.ID_User = Users.ID
						WHERE Pronostic.Score1 = 0 
						AND Pronostic.Score2 = 0
						AND Users.Email != ''
						GROUP BY Matchs.ID, Users.ID"
						);
$no_prono->execute();
$result_no_prono = $no_prono->fetchAll();


foreach($result_no_prono as $result){
	$aujourdhui = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d'), date('Y')));
	$apresdemain = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d'), date('Y')) + 24*60*60*2);
	if($result["Date"] > $aujourdhui && $result['Date'] < $apresdemain){
		$emails[$result["Email"]][] = $result["Equipe1"] . " - " . $result['Equipe2'] . " : " . $result['Date'];
	}
}
	
	var_dump($emails);
	
if(count($emails) > 0){
	foreach($emails as $email=>$matchs){
		$message = "Vous devez mettre vos pronostiques avant les matchs suivants : <br /><br />";
		foreach($matchs as $match){
			$message .= $match . "<br />";
		}
		
		$message .= "<br /> Lien du site : http://shyrel.byethost6.com";
		smtpmailer($email, 'seillier.benjamin@gmail.com', 'Benjamin SEILLIER', 'Site de pronostique', $message);
	}
}
?>