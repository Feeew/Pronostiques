
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/bootstrap-theme.min.css" rel="stylesheet">
<link href="css/myStylesheet.css" rel="stylesheet">
<link href="css/CSSTableGenerator.css" rel="stylesheet">
	

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
		<li class="active"><a href="index.php">Accueil</a></li>
		<li class="dropdown">
		  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Tournois<b class="caret"></b></a>
		  <ul class="dropdown-menu">
			<li><a href="index.php">Mes tournois actuels</a></li>
			<li class="divider"></li>
			<li><a href="inscription_tournoi.php">S'inscrire à un tournoi</a></li>
			<li><a href="index.php">Historique des tournois</a></li>
		  </ul>
		</li>
		<li><a href="index.php">Contact</a></li>
	  </ul>

	<?php
	if(isset($_SESSION['connected'])){
		if($_SESSION['connected'] == true){
			?>
				<ul class="nav navbar-nav navbar-right">
				  <li class="connectedAs">Vous êtes connecté en tant que <b><span id="username"><?php echo $_SESSION['username'];?></span></b>.</li>
				  <li><a href="../Scripts/logout.php">Se déconnecter</a></li>
				</ul>
			<?php
		}
	}
	if(!isset($_SESSION['connected']) || $_SESSION['connected'] == false){
		?>
		  <ul class="nav navbar-nav navbar-right">
		  <li><a href="addUser.php">Pas encore inscrit ?</a></li>
		  </ul>
		  <form method="post" action="login.php" class="navbar-form navbar-right">
			<div class="form-group">
				<div class="input-group connect_header">
				  <span onclick="document.getElementById('password').focus();" class="input-group-addon"><div class="glyphicon glyphicon-lock"></div></span>
				  <input tabindex=2 id="password" type="password" name="password" class="form-control" placeholder="Mot de passe" required>
				</div>
				<div class="input-group connect_header space_right_10px">
				  <span class="input-group-addon" onclick="document.getElementById('username').focus();"><div class="glyphicon glyphicon-user"></div></span>
				  <input type="text" id="username" tabindex=1 class="form-control" name="username" placeholder="Nom de compte" required>
				</div>
			</div>
			<button tabindex=3 type="submit" class="btn btn-default">Se connecter</button>
		  </form>
		<?php
	}
	?>

	</div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>