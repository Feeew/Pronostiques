<?php

if(!isset($_SESSION["connected"]) || $_SESSION["connected"] == false || !isset($_SESSION["username"]) || $_SESSION["username"] == null)

header('Location: ../Pages/index.php?err=4');

?>
