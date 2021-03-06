<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/myStylesheet.css?<?php echo time();?>" rel="stylesheet">
<link href="css/jquery-ui.min.css" rel="stylesheet">
<link href="css/jquery-ui.structure.min.css" rel="stylesheet">
<link href="css/jquery-ui.theme.min.css" rel="stylesheet">
<link href="css/jquery-ui-timepicker-addon.css" rel="stylesheet">

<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
	<!-- Brand and toggle get grouped for better mobile display -->
	<div class="navbar-header">
	  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	  </button>
	  <a class="navbar-brand" href="index.php">Le bar des pronos</a>
	</div>

	<!-- Collect the nav links, forms, and other content for toggling -->
	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	  <ul class="nav navbar-nav">
		<li><a href="index.php">Accueil</a></li>
		<li class="dropdown">
		  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Tournois<b class="caret"></b></a>
		  <ul class="dropdown-menu">
			<li><a href="mesTournois.php">Mes tournois actuels</a></li>
			<li class="divider"></li>
			<?php if(isset($_SESSION["grade"]) && $_SESSION["grade"]==2){ ?><li><a href="creation_tournoi.php">Cr&eacute;er un tournoi</a></li> <?php } ?>
			<li><a href="inscription_tournoi.php">S'inscrire &agrave; un tournoi</a></li>
			<li class="divider"></li>
			<li><a href="historiqueTournois.php">Historique des tournois</a></li>
		  </ul>
		</li>
		<li><a href="Suggestion.php">Suggestions</a></li>
		<li><a href="contact.php">Contact</a></li>
	  </ul>

	<?php
	if(isset($_SESSION['connected'])){
		if($_SESSION['connected'] == true){
			?>
				<ul class="nav navbar-nav navbar-right">
				  <li class="connectedAs">Vous êtes connect&eacute; en tant que <b><span id="username"><?php echo $_SESSION['username'];?></span></b>.&nbsp;&nbsp;</li>
				  <li><a href="../Scripts/logout.php">Se d&eacute;connecter</a></li>
				</ul>
			<?php
		}
	}
	if(!isset($_SESSION['connected']) || $_SESSION['connected'] == false){
		?>
		  <ul class="nav navbar-nav navbar-right">
		  <li><a href="addUser.php">Pas encore inscrit ?</a></li>
		  <li><a href="pass_recovery.php">Mot de passe perdu ?</a></li>
		  </ul>
		  <form method="post" action="../Scripts/login.php" class="navbar-form" style="float:right;">
			<div class="form-group">
				<div class="input-group connect_header space_right_10px" style="float:left;">
				  <span class="input-group-addon" onclick="document.getElementById('username').focus();"><div class="glyphicon glyphicon-user"></div></span>
				  <input type="text" style="height:39px;" id="username" tabindex=1 class="form-control" name="username" placeholder="Nom de compte" required>
				</div>
				
				<div class="input-group connect_header space_right_10px" style="float:right;">
				  <span onclick="document.getElementById('password').focus();" class="input-group-addon"><div class="glyphicon glyphicon-lock"></div></span>
				  <input tabindex=2 id="password" style="height:39px;" type="password" name="password" class="form-control" placeholder="Mot de passe" required>
				</div>
			</div>
			<button tabindex=3 type="submit" class="btn btn-primary">Se connecter</button>
		  </form>
		<?php
	}
	?>

	</div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>