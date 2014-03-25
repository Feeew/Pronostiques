<?php
include '../Pages/session_start.php';

$_SESSION["connected"] = false;
$_SESSION["username"] = null;

header('Location: ../Pages/index.php');

?>
