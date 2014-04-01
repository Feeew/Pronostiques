<?php
include '../Pages/session_start.php';

$_SESSION["connected"] = false;
$_SESSION["username"] = null;
$_SESSION["grade"] = null;

header('Location: ../Pages/index.php');

?>
