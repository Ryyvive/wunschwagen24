<?php
session_start();
$valid = true;
$login = false;
if (isset($_POST["email"]) && isset($_POST["password"])){
    $email = htmlspecialchars($_POST["email"]);
    $password_user = htmlspecialchars($_POST["password"]);

    $sql_check_mail = "SELECT vorname FROM customer WHERE email = '".$email. "' and WHERE password = ".$password_user;
    $serverName = "wunschwagen24-dbserver-dev.database.windows.net";
    $connectionOptions = array(
        "Database" => "wunschwagen-db-dev",
        "Uid" => "CloudSA1cb8415e",
        "PWD" => "340Uuxwp7Mcxo7Khy"
    );

    $conn = sqlsrv_connect($serverName, $connectionOptions);
    $res = sqlsrv_query($conn, $sql_check_mail);
    if(sqlsrv_num_rows($res) == 1){
        $valid = true;
        $login = true;
        header("Location: index.php");
    }else{
        $valid = false;
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
              <form class="login-form" method="post" action="login.php">
                  <span style="margin: auto"><h1>WunschWagen24 Login</h1></span>
                  <?PHP if(!$valid){
                  echo '<span style="margin: auto color :red">Email oder Passwort sind incorrect</span>';
                  } ?>
                <input required id="email" name="email" type="email" class="input" placeholder=<?php if(isset($email)){echo $email;}else{echo "Email";}?>>
                <input required id="password" name="password" type="password" class="input" placeholder="Password">
                <p class="page-link">
                    <span class="page-link-label">Passwort vergessen?</span>
                </p>
                <input type="submit" class="form-btn" value = "Log in">
            </form>
            <p class="sign-up-label">
                Noch keinen Account?<span class="sign-up-link"><a href="register.php">Registrieren</a></span>
            </p>
        </div>
        </div>
    </body>
</html>