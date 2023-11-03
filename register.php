<?php
session_start();
$valid = true;
$login = false;
if (isset($_POST["email"]) && isset($_POST["vorname"]) && isset($_POST["nachname"]) && isset($_POST["password"])){
    $vorname = htmlspecialchars($_POST["vorname"]);
    $nachname = htmlspecialchars($_POST["nachname"]);
    $email = htmlspecialchars($_POST["email"]);
    $password_user = htmlspecialchars($_POST["password"]);

    $sql_check_mail = "SELECT email FROM customer WHERE email = '".$email. "'";
    $serverName = "wunschwagen24-dbserver-dev.database.windows.net"; // update me
    $connectionOptions = array(
        "Database" => "wunschwagen-db-dev", // update me
        "Uid" => "CloudSA1cb8415e", // update me
        "PWD" => "340Uuxwp7Mcxo7Khy" // update me
    );

// Create connection
    $conn = sqlsrv_connect($serverName, $connectionOptions);
    $res = sqlsrv_query($conn, $sql_check_mail);
    if(sqlsrv_has_rows($res)){
        $valid = false;
        $login = false;
    }else{
        $sql_insert = "INSERT INTO Customer (email, vorname, nachname, password) VALUES ('".$email."','".$vorname."','".$nachname."','".$password_user."')";
        sqlsrv_query($conn, $sql_insert);
        $_SESSION["email"] = $email;
        $_SESSION["vorname"] = $vorname;
        header("Location: index.php");
    }

}
?>

<html lang="DE">
<head>
    <title>Anmeldung</title>
    <link rel="stylesheet" href="./styles.css">
    <link rel="icon" href="branding/logo_small_icon_only.png">
</head>
<body>
<div class = main-content style="background: transparent">
    <a href="index.php">
        <img src="branding/logo_small_icon_only_inverted.png">
    </a>
    <div class="breaker"></div>
    <div class="form-container">
        <form class="login-form" method="post" action = "register.php">
            <span style="margin: auto"><h1>WunschWagen24 Login</h1></span>
            <?PHP if(!$valid){
                echo '<span style="margin: auto; color :red">Email ist bereits vergeben <a href = "login.php">zum Login</a></span>';
            } ?>
            <input required id="email" name="email" type="email" class="input" placeholder="Email">
            <input required id="vorname" name="vorname" type="text" class = "input" placeholder="Vorname">
            <input required id="nachname" name="nachname" type="text" class = "input" placeholder="Nachname">
            <input required id="password" name="password" type="password" class="input" placeholder="Password">
            <p class="page-link">
                <span class="page-link-label">Passwort vergessen?</span>
            </p>
            <input type="submit" class="form-btn" value = "Registrieren">
        </form>
    </div>
</div>
</body>
</html>