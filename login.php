<?php
session_start();
?>

<html lang="DE">
    <head>
        <title>Anmeldung</title>
        <link rel="stylesheet" href="./styles.css">
    </head>
    <body>
        <div class = main-content style="background: transparent">
            <a href="index.php">&lt&ltZurück zur Startseite</a>
            <img src="branding/logo_small_icon_only_inverted.png">
            <div class="breaker"></div>
        <div class="form-container">
              <form class="login-form">
                  <span style="margin: auto"><h1>WunschWagen24 Login</h1></span>
                <input type="email" class="input" placeholder="Email">
                <input type="password" class="input" placeholder="Password">
                <p class="page-link">
                    <span class="page-link-label">Passwort vergessen?</span>
                </p>
                <button class="form-btn">Log in</button>
            </form>
            <p class="sign-up-label">
                Noch keinen Account?<span class="sign-up-link">Registrieren</span>
            </p>
        </div>
        </div>
    </body>
</html>