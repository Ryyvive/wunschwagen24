<?php
session_start();

$serverName = "wunschwagen24-dbserver-dev.database.windows.net";
$connectionOptions = array(
    "Database" => "wunschwagen-db-dev",
    "Uid" => "CloudSA1cb8415e",
    "PWD" => "340Uuxwp7Mcxo7Khy"
);
$conn = sqlsrv_connect($serverName, $connectionOptions);
if (isset($_POST["suchtyp"])) {
    $_SESSION["POST"] = $_POST;
    unset($_POST["suchtyp"]);
}
function search_AI()
{
    $serverName = "wunschwagen24-dbserver-dev.database.windows.net";
    $connectionOptions = array(
        "Database" => "wunschwagen-db-dev",
        "Uid" => "CloudSA1cb8415e",
        "PWD" => "340Uuxwp7Mcxo7Khy"
    );
    $con = sqlsrv_connect($serverName, $connectionOptions);

    ## auf Suche Exact zutreffen
    #TODO: Komplexe SQL Abfrage zum Suchen von genau passenden Angeboten
    if (isset($_POST["brand"])) {
        $input_variable = array(
            "brand" => $_POST["brand"],
            "modell" => $_POST["modell"]
        );
    }
    ## AI zum Heraussuchen des am besten passenden Wagens
    #TODO: Bewertungsalgorithmus zum Bewerten (Scoring) welcher Wagen am besten geeignet ist

    #Testing for Programming reasons
    $test_sql = "Select * From dbo.cars";
    $result = sqlsrv_query($con, $test_sql);
    while ($car = sqlsrv_fetch_array($result)) {
        echo "<tr>";
        #Bild des Autos
        #TODO: API Anbindung
        echo "<td>";
        echo "Kein Bild verfügbar";
        echo "</td>";
        # Eigenschaften des Autos
        echo "<td>";
        echo "Marke: " . $car["brand"] . "<br>";
        echo "Modell: " . $car["modell"] . "<br>";
        echo "Preis: " . $car["preis"] . " €<br>";
        echo "</td>";
        echo "<td>";
        echo "Erstzulassung: " . $car["erstzulassung"]->format('Y') . "<br>";
        echo "Leistung: " . $car["leistung"] . " PS<br>";
        echo "Kraftstoff: " . $car["kraftstoff"] . "<br>";
        echo "</td>";
        ?>
        <td>
            <button onclick="window.location.href='search.php?id=<?php echo $car["id"] ?>'">Zum Auto</button>
        </td>
        <?php
        echo "</tr>";
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
<header></header>
<div class="breaker"></div>
<div class="main-content">
    <?php
    if (!isset($_GET["id"])) {
        if (!isset($_SESSION["POST"]["suchtyp"]) || $_SESSION["POST"]["suchtyp"] == "NEU") {
            ?>
            <form name="suchtyp-einstellung" method="POST">
                <h1>Wie hätten Sie es gerne?</h1>
                <p>Unsere Entwickler sind auf dem neusten Stand der Technik und bieten ihnen mit KI die Möglichkeit über
                    ein paar Fragen das perfekte Auto zur Verfügung zu stellen.</p>
                <h2>Sie wissen schon, was Sie suchen?</h2>
                <p>Auch das ist kein Problem. Mit einfachen Filtern ermöglichen wir Ihnen Zugriff auf Deutschlands
                    größte Gebrauchtwagendatenbank.</p>
                <input type="radio" name="suchtyp" id="KI" value="KI" checked/><label for="KI">Ich möchte mich beraten
                    lassen</label>
                <input type="radio" name="suchtyp" id="ML" value="ML"/><label for="ML">Ich weiß, wonach ich
                    suche</label>
                <br>
                <input type="submit" value="Auf geht's">
            </form>

            <?php
        } else if ($_SESSION["POST"]["suchtyp"] == "ML") {
            ?>
            <h1> Deutschlands größte Gebrauchtswagen Datenbank</h1>
            <form class="search" method="POST" action="search.php">
                <label for="brand">Marke</label>
                <select id="brand" name="brand">
                    <option selected value="*"></option>
                    <?php
                    $sqlstatement = "SELECT DISTINCT brand from cars";
                    $res = sqlsrv_query($conn, $sqlstatement);
                    while ($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)) {
                        echo "<option value =" . $row["brand"] . ">" . $row["brand"] . "</option>";
                    }
                    ?>
                </select>

                <label for="modell">Modell</label>
                <select id="modell" name="modell">
                    <option selected value="*"></option>
                    <?php
                    $sqlstatement = "SELECT DISTINCT modell from cars";
                    $res = sqlsrv_query($conn, $sqlstatement);
                    while ($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)) {
                        echo "<option value =" . $row["modell"] . ">" . $row["modell"] . "</option>";
                    }
                    ?>
                </select>

                <label for="price">Maximaler Preis</label>
                <select id="price" name="price">
                    <option selected value="*"></option>
                    <?php
                    $minstatement = "Select MIN(Preis) as Preis FROM CARS";
                    $maxstatement = "Select MAX(Preis) as Preis FROM Cars";
                    $minres = sqlsrv_query($conn, $minstatement);
                    $maxres = sqlsrv_query($conn, $maxstatement);
                    $min = round(sqlsrv_fetch_array($minres, SQLSRV_FETCH_ASSOC)["Preis"], -4);
                    $max = round(sqlsrv_fetch_array($maxres, SQLSRV_FETCH_ASSOC)["Preis"], -4);
                    for ($price = $min; $price <= $max; $price += 5000) {
                        echo "<option value =" . $price . ">" . $price . "</option>";
                    }
                    ?>
                </select>

                <label for="kilometers">Kilometerstand</label>
                <select id="kilometers" name="kilometers">
                    <option selected value="*"></option>
                    <?php
                    $minstatement = "Select MIN(kilometerstand) as kilometerstand FROM CARS";
                    $maxstatement = "Select MAX(kilometerstand) as kilometerstand FROM Cars";
                    $minres = sqlsrv_query($conn, $minstatement);
                    $maxres = sqlsrv_query($conn, $maxstatement);
                    $min = round(sqlsrv_fetch_array($minres, SQLSRV_FETCH_ASSOC)["kilometerstand"], -5);
                    $max = round(sqlsrv_fetch_array($maxres, SQLSRV_FETCH_ASSOC)["kilometerstand"], -5);
                    for ($stand = $min; $stand <= $max; $stand += 5000) {
                        echo "<option value =" . $stand . ">" . $stand . "</option>";
                    }
                    ?>
                </select>
            </form>
            <?php
        } else if ($_SESSION["POST"]["suchtyp"] == "KI") {
            ?>
            <form class="search" method="POST" action="search.php?">
                <h1>Wie funktioniert unsere KI?</h1>
                <p> Wir stellen Ihnen nun ein paar Fragen. Ihre Antworten werden anschließend analysiert und Sie
                    bekommen die besten Gebrauchtwagen vorgeschlagen</p>
                <h2>Wofür wollen Sie das Auto Nutzen?</h2>
                <label for="nutzart"></label>
                <select id="nutzart" name="nutzart">
                    <option value="1">Familienfahrzeug</option>
                    <option value="2">Pendeln</option>
                    <option value="3">Wochenendfahrten</option>
                    <option value="4">Saisonfahren</option>
                </select>
                <h2>Wieviele Personen sollten mindestens in das Auto passen?</h2>
                <label for="personen"></label>
                <input type="number" min="1" max="9" name="personen" id="personen" value="1">
                <h2>Wieviel wollen Sie maximal für den Wagen ausgeben?</h2>
                <label for="budget"></label>
                <input type="number" min="1000" name="budget" id="budget" value="1000" step="1000">
                <h2>Welche Getriebeart bevorzugen Sie?</h2>
                <input type="radio" name="getriebe" id="auto" value="auto" checked/><label for="auto">Automatik</label>
                <input type="radio" name="getriebe" id="manuel" value="manuel"/><label
                        for="manuel">Schaltgetriebe</label>
                <h2>Wie wichtig ist Ihnen eine hochwertige Innenausstatung?</h2>
                <label for="innenausstatung"></label>
                <select id="innenausstatung" name="innenausstatung">
                    <option value="1">Sehr wichtig - besonders hochwertig</option>
                    <option value="2">Eher wichtig</option>
                    <option value="3">Vernachlässigbar</option>
                </select>
                <h2>Welche Antriebsart soll ihr Auto haben?</h2>
                <input type="checkbox" id="Autogas" name="Autogas" value="Autogas">
                <label for="Autogas">Autogas</label><br>
                <input type="checkbox" id="Diesel" name="Diesel" value="Diesel">
                <label for="Diesel"> Diesel</label><br>
                <input type="checkbox" id="Benzin" name="Benzin" value="Benzin">
                <label for="Benzin"> Benzin</label><br>
                <input type="checkbox" id="Elektrisch" name="Elektrisch" value="Elektrisch">
                <label for="Elektrisch"> Elektrisch</label><br>
                <h2>Benötige Leistung</h2>
                <input type="number" name="leistung" id="leistung" max="300">
                <label for="leistung">PS</label>
                <input type="hidden" name="suchtyp" id="suchtyp" value="DONE">
                <h3>Alles gescheckt?</h3>
                <input type="submit" value="Jetzt zum Traumauto">
            </form>
            <?php
        } else if ($_SESSION["POST"]["suchtyp"] == "DONE") {
            ?>
            <h2>Nicht so voreilig</h2>
            <p>Das Ergebnis der Suche kommt schon noch. Entwicklung braucht eben seine Zeit</p>
            <form method="post">
                <input type="hidden" name="suchtyp" id="suchtyp" value="NEU">
                <input type="submit" value="Neue Suche!">
            </form>
            <table>
                <?php
                search_AI();
                ?>
            </table>

            <?php
        }
    } else {
        ?>
        <button onclick="window.location.href='search.php?'">Zurück zur Suche</button>
    <?php
    }
    ?>
</div>
</body>
</html>