<?php 
include 'session_start.php';
include '../Scripts/test_session.php';
if(!isset($_SESSION['connected']) || $_SESSION['connected'] == false){
	header('location', 'index.php');
}
?>
<html>
<HEAD>
	<title>Mes tournois</title>
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
	$sql = "SELECT * FROM Tournoi WHERE ID IN (Select ID_Tournoi FROM Inscriptions WHERE ID_User = (SELECT ID FROM Users WHERE Username = '".strtoupper($_SESSION["username"])."')) AND DateFin > NOW() - 10;";
	$stmt = $db->prepare($sql);
	$stmt->execute();
	$result = $stmt->fetchAll();
	
	$i = 0;
	
	$tournois = array();
	
	foreach($result as $row){
		$tournois[$i] = $row;
		$i++;
	}

	?>
		<h1>Mes tournois</h1>
		<br/>
		<?php 
		if(count($tournois) == 0){ 
			echo "<h4> Vous ne participez &agrave; aucun tournoi actuellement.</h4>";
		}
		else{ 
			?>
			<h4>Tournois auxquels vous participez :</h4><br />
			<form action='tournoi.php' id='form_tournoi' method='post'>
			<table>
			<?php
				for($i = 0; $i<count($tournois); $i++){
					$sport = (($tournois[$i]['Sport'] == "foot") ? "Football" : (($tournois[$i]['Sport'] == "rugby") ? "Rugby" : ""));
					?>
						<tr>
							<td>
								<div class="row">
								  <div class="col-lg-6">
									<div class="input-group">
									  <span class="input-group-btn">
										<?php 
											echo "<button onclick='go_to_tournoi(\"".$tournois[$i]['ID']."\",\"".str_replace(' ', '_', $tournois[$i]['Nom'])."\", \"".$sport."\")' class='btn btn-default' type='button'>Voir</button>";
										?>
									  </span>
									  <span class="tournoi_name" onclick='go_to_tournoi("<?php echo $tournois[$i]['ID'];?>", "<?php echo str_replace(' ', '_', $tournois[$i]['Nom'])?>", "<?php echo $sport; ?>")'><?php echo $tournois[$i]['Nom'];?><span style='float:right'> - <?php echo $sport; ?></span></span>
									</div><!-- /input-group -->
								  </div><!-- /.col-lg-6 -->
								</div><!-- /.row -->
							</td>
						</tr>

					<?php
				}
			?>
			</table>
			<input type='hidden' id="tournoi_id" value="" name='tournoi_id'/>
			<input type='hidden' value='' id="tournoi_nom" name="tournoi_nom"/>
			<input type='hidden' value='' id="tournoi_sport" name="tournoi_sport"/>
		</form>
			<?php
		}
		?>
		<br/><br/>
	<?php

?>
</div>
</div>

<?php
	include './footer.php';
?>

</body>
</html>