<?

include 'session_start.php';

include '../Scripts/global.php';

require_once('../Scripts/PHPMailer-master/PHPMailerAutoload.php');

include 'header.php';



function smtpmailer($to, $from, $from_name, $subject, $body) { 

  global $error;

  $mail = new PHPMailer();  // create a new object

  $mail->IsSMTP(); // enable SMTP

  $mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only

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

$sql = "SELECT * FROM Users";
if(count($_POST)){
	foreach ($db->query($sql) as $row) {
		if($_POST['email'] == $row['Email']){
			$token = md5(time() . mt_rand());		
			$message = 'Vous avez demande a redefinir votre mot de passe , Merci de cliquer sur le lien ci dessous.
			http://shyrel.byethost6.com/Pages/newpass.php?e='.urlencode ($_POST["email"]).'&t='.urlencode ($token).'';
      smtpmailer($row['Email'], 'benji.seillier@gmail.com', 'Benjamin SEILLIER', 'Site de pronostique', $message);
      //db update token here
      $sql1 = "UPDATE `Users` SET  Token = '".$token."' WHERE Email = '".$_POST['email']."' ";
      $db->exec($sql1);
      echo '<div class="alert alert-success"><strong>Success!</strong> Une email vous a &eacute;t&eacute; envoy&eacute; ! </div>';
		}
	}
	
	 
}


?>


<div id="wrapper">


<div id="content">
    
<h1>Mot de passe oubli&eacute; ?</h1></br>    

<form id="register-form" role="form" autocomplete="off" class="form" method="post">

<div class="form-group">
<div class="input-group">
<span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
<input id="email" name="email" placeholder="email" class="form-control"  type="email" >
</div>
<div class="form-group">
<input name="recover-submit" class="btn btn-primary btn-block" value="Reset Password" type="submit" style="margin-top: 1em;>
</div>
</div>
<input type="hidden" class="hide" name="token" id="token" value=""> 
</form>

</div>
</div>


