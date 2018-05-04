<?php
include 'session_start.php';
include '../Scripts/test_session.php';

include '../Scripts/global.php';

?>
<html>
<head>
	<title>Cr&eacute;ation d'un tournoi</title>
</head>
<body>


<?php
	include 'header.php';
?>

<div id="wrapper">

<div id="content">

<h1>Cr&eacute;ation d'un tournoi</h1></br>
<?php

if(!isset($_POST['Nom']) && !isset($_POST['DateFin']))
{	
echo "<h5>Afin de cr&eacute;er un tournoi, merci d'indiquer son nom et sa date de fin (format: 2015-12-31).</h5>";
?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="">
		<div class="form-group">
			<div class="input-group formAjout">
			  <span class="input-group-addon" onclick="document.getElementById('Nom').focus();"><div class="glyphicon glyphicon-font"></div></span>
			  <input type="text" id="Nom" tabindex=3 class="form-control" name="Nom" placeholder="Nom du tournoi" required /	>
			</div>
			<div class="input-group formAjout">
			  <span onclick="document.getElementById('DateFin').focus();" class="input-group-addon"><div class="glyphicon glyphicon-calendar"></div></span>
			  <input tabindex=4 id="DateFin" type="text" name="DateFin" class="form-control" placeholder="Date de fin (YYYY-MM-DD)" required />
			</div>
			<div class="input-group formAjout">
			  <span onclick="document.getElementById('Sport').focus();" class="input-group-addon"><div class="glyphicon glyphicon-th-list"></div></span>
			  <select class="form-control" tabindex=5 name="Sport" id="Sport"><option value='foot'>Football</option><option selected value="rugby">Rugby</option></select>
			</div>
		</div>
		<button tabindex=6 type="submit" class="btn btn-default">Cr&eacute;er tournoi</button>
	</form>
<?php
}
else
{ 
	$user_id = $_SESSION["user_id"];
	$datecreation = date("Y-m-d");
	$tournoi_nom = $_POST["Nom"];
	$tournoi_dateFin = $_POST["DateFin"];
	$sport = $_POST["Sport"];
	
	try{
		$sql = $db->prepare("SELECT * FROM Tournoi WHERE Nom = :Nom");
		$sql->execute(array(
			'Nom' => $tournoi_nom
		));
		$result = $sql->rowCount();
		
		if($result > 0){
			echo "Erreur dans la cr&eacute;ation du tournoi : Ce tournoi existe d&eacute;j&agrave; (le nom est d&eacute;j&agrave; pris).";
			
			echo "<br />";
			
			echo "<a href='creation_tournoi.php'>Retour &agrave; la cr&eacute;ation d'un tournoi</a>";
		}
		else{
			$sql = $db->prepare("INSERT INTO Tournoi (Nom, DateFin, User_id, DateCreation, Sport) VALUES (:Nom, :DateFin, :user_id, :datecreation, :sport)");
			$result = $sql->execute(array(
				'Nom'	=> $tournoi_nom,
				'DateFin'	=> $tournoi_dateFin,
				'user_id'	=> $user_id,
				'datecreation'	=> $datecreation,
				'sport'	=> $sport,
			));
			
			echo "<b>Cr&eacute;ation termin&eacute;e. N'oubliez pas de vous y inscrire !</b>"; 
			echo "<br />";
			echo "<a href='inscription_tournoi.php'>Retour &agrave; la liste des tournois disponibles</a>";

			require_once('../Scripts/PHPMailer-master/PHPMailerAutoload.php');



			define('GUSER', 'benji.seillier@gmail.com'); // GMail username

			define('GPWD', 'paddle62'); // GMail password



			function smtpmailer($to, $from, $from_name, $subject, $body) { 

				global $error;

				$mail = new PHPMailer();  // create a new object

				$mail->IsSMTP(); // enable SMTP

				$mail->SMTPDebug = 2;  // debugging: 1 = errors and messages, 2 = messages only

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

			$message .= "Bonjour, <br /> Un nouveau tournoi vient de commencer <br /> Titre : ".$tournoi_nom." <br /> Pour vous inscrire rendez vous &agrave; cette URL <br /> http://shyrel.byethost6.com/Pages/inscription_tournoi.php";

			$request = $db->prepare("SELECT Email FROM Users WHERE Email != '' ");
			$request->execute();
			$result = $request->fetchAll();
			foreach ($result as $row) {
				smtpmailer($row['Email'], 'seillier.benjamin@gmail.com', 'Benjamin SEILLIER', 'Site de pronostique', $message);
			}
			
			}
		}

	catch(Exception $e){
		echo "Erreur dans la cr&eacute;ation du tournoi : ".$e->getMessage();
	}
}
?>

</div>

</div>



<?php
	include 'footer.php';
?>

</body>

<script type="text/javascript">
  $(function() {
    $( "#DateFin" ).datepicker({ dateFormat: 'yy-mm-dd' });
  });
</script>
</html>