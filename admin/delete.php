<?php 
session_start();
require "../config/config.php";

$stmt = $pdo->prepare("DELETE FROM posts WHERE id=".$_GET['id']);
$stmt->execute();


echo "<script>window.location.href='index.php'</script>";
?>