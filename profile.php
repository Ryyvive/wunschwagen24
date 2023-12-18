<?php
session_start();
if(isset($_GET["logout"])){
    unset($_SESSION["email"]);
    unset($_SESSION["vorname"]);
    header("Location:index.php");
}
?>
<html lang="DE">
<head>
    <title>Wunschwagen 24 - Dein Gebrauchtwagen</title>
    <link rel="stylesheet" href="./styles.css">
    <link rel="icon" href="branding/logo_small_icon_only.png">
</head>
<body>
<header>
    <?php include("header.php") ?>
</header>
<div class="breaker"></div>
<div class="main-content">
    <button type="submit" onclick="window.location.href='profile.php?logout=true'">Abmelden</button>