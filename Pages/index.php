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
	$stmt = $db->prepare("SELECT * FROM Tournoi WHERE ID IN (Select ID_Tournoi FROM Inscriptions WHERE ID_User = (SELECT ID FROM Users WHERE Username = '".strtoupper($_SESSION["username"])."'))");
	$stmt->execute();
	$result = $stmt->fetchAll();
	
	$i = 0;
	
	$tournois = array();
	
	foreach($result as $row){
		$tournois[$i] = $row;
		$i++;
	}

	?>
		<h1>Bonjour <?php echo $_SESSION["username"]; ?> !</h1>
		<br/>
		<?php 
		if(count($tournois) == 0){ 
			echo "<h3> Vous ne participez à aucun tournoi actuellement.</h3>";
		}
		else{ 
			?>
			<h3>Tournois auxquels vous participez :</h3>
			<form action='tournoi.php' id='form_tournoi' method='post'>
			<table>
			<?php
				for($i = 0; $i<count($tournois); $i++){
					?>
						<tr>
							<td>
								<div class="row">
								  <div class="col-lg-6">
									<div class="input-group">
									  <span class="input-group-btn">
										<?php 
											echo "<button onclick='go_to_tournoi(\"".$tournois[$i]['ID']."\",\"".str_replace(' ', '_', $tournois[$i]['Nom'])."\")' class='btn btn-default' type='button'>Go !</button>";
										?>
									  </span>
									  <input type="text" disabled value='<?php echo $tournois[$i]['Nom'];?>' class="form-control tournoi_inscription">
									</div><!-- /input-group -->
								  </div><!-- /.col-lg-6 -->
								</div><!-- /.row -->
							</td>
						</tr>

					<?php
					echo "<br />";
				}
			?>
			</table>
			<input type='hidden' id="tournoi_id" value="" name='tournoi_id'/>
			<input type='hidden' value='' id="tournoi_nom" name="tournoi_nom"/>
		</form>
			<?php
		}
		
		echo "<br /><br /><a href='inscription_tournoi.php'>S'inscrire à un tournoi</a>";
		?>
		<br/><br/>
	<?php
}

?>
</div>
</div>

<?php
	include './footer.php';
?>

</body>
</html>