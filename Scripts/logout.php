<?php
session_start();

$_SESSION["connected"] = false;
$_SESSION["username"] = null;

header('Location: ../Pages/index.php');

?>
