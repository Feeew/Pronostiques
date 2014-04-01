<?php

if(!isset($_SESSION["connected"]) || $_SESSION["connected"] == false || !isset($_SESSION["username"]) || $_SESSION["username"] == null || !isset($_SESSION["grade"]) || $_SESSION["grade"] == null)

header('Location: ../Pages/index.php?err=4');

?>
