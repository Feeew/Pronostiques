<?php
include '../Scripts/global.php';

$msg = $_POST["text"];
$id = $_POST["id"];
$sql = $db->prepare('UPDATE Messagerie SET message = "'.$msg.'" WHERE id = '.$id.' ');
$result = $sql->execute();
?>