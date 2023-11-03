<?php
session_start();
$serverName = "wunschwagen24-dbserver-dev.database.windows.net"; // update me
$connectionOptions = array(
    "Database" => "wunschwagen-db-dev", // update me
    "Uid" => "CloudSA1cb8415e", // update me
    "PWD" => "340Uuxwp7Mcxo7Khy" // update me
);

// Create connection
$conn = sqlsrv_connect($serverName, $connectionOptions);
echo (sqlsrv_errors());
// Check connection
echo "Connected successfully";
?>
<html lang="DE">
<head>
    <title>Wunschwagen 24 - Dein Gebrauchtwagen</title>
    <link rel="stylesheet" href="./styles.css">
    <link rel="icon" href="branding/logo_small_icon_only.png">
</head>
<body>
<header>
    <div class="topnav">
        <table>
            <tbody>
            <tr>
                <th>
                    <img src="branding/logo_large.png" alt="">
                </th>
                <th>
                    <a href="search.php">Suchen</a>
                    <a href>Verkaufen</a>
                    <a href>Beratung</a>
                </th>
                <th>
                    <?php
                    if(isset($_SESSION["username"])){
                        echo "<h1>Willkommen ".$_SESSION["vorname"]."</h1>";
                    }else{
                        ?>
                        <button onclick="window.location.href='login.php'">Anmelden</button>
                    <?php
                    }
                    ?>
                </th>
            </tr>
            </tbody>
        </table>
    </div>
</header>
<div class="breaker"></div>
<div class="main-content">
    <h1>
        Willkommen bei WunschWagen24 - Ihre Traumfahrzeuge, nach Ihren Wünschen.
    </h1>
    <p>
        Bei WunschWagen24 dreht sich alles um Sie und Ihr Fahrerlebnis.</br>
        Wir sind mehr als nur ein Unternehmen, das sich auf Gebrauchtwagen spezialisiert hat.</br>
        Wir sind Ihre Partner, wenn es darum geht, Ihr Traumauto Wirklichkeit werden zu lassen.
    </p>
    <h1>
        Wer sind wir?
    </h1>
    <p>
        Wir sind ein leidenschaftliches Team von Automobil-Enthusiasten und Experten im Fahrzeugmarkt.
        Unser Ziel ist es, Ihnen eine einzigartige und maßgeschneiderte Möglichkeit zu bieten,
        Ihr ideales Gebrauchtfahrzeug zu finden.
        Wir glauben daran, dass jedes Auto eine Geschichte hat, und wir helfen Ihnen dabei,
        diejenige zu entdecken, die zu Ihnen passt.
    </p>
    <h1>
        Was machen wir?
    </h1>
    <p>
        WunschWagen24 ermöglicht es Ihnen, nach Gebrauchtwagen zu suchen und sie nach Ihren individuellen Wünschen zu
        konfigurieren. Unser benutzerfreundliches Online-Tool bietet eine breite Auswahl an Optionen, damit Sie Ihr
        Fahrzeug ganz nach Ihren Vorstellungen gestalten können. Egal, ob Sie nach einem zuverlässigen Alltagsfahrzeug,
        einem sportlichen Cabrio oder einem geräumigen Familien-SUV suchen - bei uns finden Sie, was Sie suchen.
    </p>
    <p>
        Unsere Plattform bringt Käufer und Verkäufer von Gebrauchtwagen zusammen und ermöglicht es Ihnen, genau das Auto
        zu finden, das Ihren Anforderungen entspricht. Wir stehen Ihnen zur Seite, vom ersten Suchklick bis zur
        Unterzeichnung des Kaufvertrags.
    </p>
    <p>
        Bei WunschWagen24 setzen wir auf Transparenz, Vertrauen und Kundenzufriedenheit. Unser Engagement für Qualität
        und Service spiegelt sich in jedem Aspekt unserer Arbeit wider.
    </p>
    <h1>
        Ihr Traumwagen ist nur einen Klick entfernt.
    </h1>
    <p>
        Starten Sie noch heute Ihre Suche auf WunschWagen24 und entdecken Sie die Vielfalt der Gebrauchtwagenwelt, die
        sich an Ihre Wünsche anpasst. Wir freuen uns darauf, Ihnen bei der Verwirklichung Ihres Traumautos zu helfen.
    </p>
    <h2>
        WunschWagen24 - Gemeinsam machen wir Ihre Autoträume wahr.
    </h2>

</div>
</body>
</html>