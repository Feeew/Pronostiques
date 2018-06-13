<?php  

include 'session_start.php';

include '../Scripts/global.php';

include 'header.php';
	
$sql = "SELECT * FROM Users WHERE Email = '".$_GET["e"]."' ";
foreach ($db->query($sql) as $row) {
  if($_GET["e"] == $row['Email']){
    $token = $row['Token'];
  }
}	

if (isset($_GET["e"]) && isset($_GET["t"]) && $_GET["t"] == $token){
    if (count($_POST)!=0){
    	$password = isset($_POST["password"]) ? md5($_POST["password"]) : null;
    	$password2 = isset($_POST["password2"]) ? md5($_POST["password2"]) : null;
    	$errors=array();
      $email = $_GET["e"];
    	if ($password == null || $password == ""){
        	$errors['password'] = "Veuillez entrer un mot de passe";
      }
    	if ($password2 == null || $password2 == ""){
        	$errors['password2'] = "Veuillez entrer la confirmation mot de passe";
      }
    	if ($password != $password2 ){
        	$errors['password3'] = "Les deux champs ne sont pas identiques";
      }
    	if (count($errors) > 0){
          $errors['password4'] = "Erreur";
    	}
      if ($_GET["t"] == $token){
        $token2 = "empty";
        $magic_key = "0c1e2u3m4k5a6d7v8l9m10d11p12";
        $password = strtoupper(md5(sha1($_POST["password"]).$magic_key));
        $sql = "UPDATE `Users` SET  Token = '".$token2."', Password = '".$password."' WHERE Email = '".$email."' ";
        $db->exec($sql);
        echo '<div class="alert alert-success"><strong>Success!</strong> Votre mot de passe a bien &eacute;t&eacute; modifier.</div>';
      }  
      else
      {
        $errors['password4'] = "Erreur";
      }
    }
}
else{
  header('Location: index.php');
}

?>


<div class="form-gap"></div>
<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4" style="margin-top: 10em;">
            <div class="panel panel-default">
              <div class="panel-body">
                <div class="text-center">
                  <h3><i class="fa fa-lock fa-4x"></i></h3>
                  <h2 class="text-center">Mot de passe oubli&eacute; ?</h2>
                  <div class="panel-body">
                    <span id='error'><?php if (isset($errors['password'])) {echo $errors['password'];}  ?></span>
                    <span id='error'><?php if (isset($errors['password2'])) {echo $errors['password2'];}  ?></span>
                    <span id='error'><?php if (isset($errors['password3'])) {echo $errors['password3'];}  ?></span>
                    <span id='error'><?php if (isset($errors['password4'])) {echo $errors['password4'];}  ?></span>
                 <form id="register-form" role="form" autocomplete="off" class="form" method="post" action="newpass.php?e=<?=$_GET["e"]?>&t=<?=$_GET["t"]?> ">
    
                      <div class="form-group">
                        <div class="input-group" style="margin-bottom: 10px;">
                          <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                          <input id="email" value="<?=$_GET["e"]?>" name="email" placeholder="email" class="form-control"  type="email">
                          </div>
                          <div class="input-group" style="margin-bottom: 10px;">
                          <span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue"></i></span>
                          <input id="password" name="password" placeholder="Mot de passe" class="form-control"  type="password">
                          </div>
                          <div class="input-group" style="margin-bottom: 10px;">
                          <span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue"></i></span>
                          <input id="password2" name="password2" placeholder="V&eacute;rification de mot de passe" class="form-control" type="password">
                        </div>
                        
                      </div>
                      <div class="form-group">
                        <input name="recover-submit" class="btn btn-lg btn-primary btn-block" value="Reset Password" type="submit">
                      </div>
                      
                      <input type="hidden" class="hide" name="token" id="token" value=""> 
                    </form>
    
                  </div>
                </div>
              </div>
            </div>
          </div>
	</div>
</div>
