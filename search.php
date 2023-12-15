<?php
session_start();
if(isset($_POST["suchtyp"])){
$_SESSION["POST"] = $_POST;
}
unset($_POST);
$connectionOptions = array(
    "Database" => $_SERVER["APPSETTING_Database"],
    "Uid" =>  $_SERVER["APPSETTING_Uid"],
    "PWD" =>  $_SERVER["APPSETTING_PWD"]
);

$conn = sqlsrv_connect($_SERVER["APPSETTING_serverName"], $connectionOptions);
function search_AI($conn): void
{
    $sql_con = $conn;#sqlsrv_connect($_SERVER["APPSETTING_serverName"], $options);

    ## auf Suche Exact zutreffen
    #TODO: Komplexe SQL Abfrage zum Suchen von genau passenden Angeboten
    if ($_SESSION["POST"]["suchtyp"] == "DONE-ML") {
        #TODO: Exakte Suche
        $sql_search_exact = "Select * from dbo.cars where brand LIKE '" .
            $_SESSION["POST"]["brand"] .
            "' and model LIKE '" .
            $_SESSION["POST"]["model"] .
            "' and price <= " .
            $_SESSION["POST"]["price"] .
            " and mileage <= " .
            $_SESSION["POST"]["mileage"] .
            "";
        $sql_result_exact = sqlsrv_query($sql_con, $sql_search_exact);
        if ($sql_result_exact && sqlsrv_has_rows($sql_result_exact)) {
            ?>
            <h1>Ihre Suchergebnisse:</h1>
            <?php
            func_create_html_table($sql_search_exact);
        }
        ?>
        <h1>Basierend auf ihrer Anfrage</h1>
        <?php
        #TODO: Empfehlungen
        #IDEE: Erst unwichitge Attrivute wegnehmen - bis modell, niemals typ

        ## For DEV purposes
        $sql_search_recommend = "Select * From dbo.cars";
        func_create_html_table($sql_search_recommend);
    } else {
        ## AI zum Heraussuchen des am besten passenden Wagens
        #TODO: Bewertungsalgorithmus zum Bewerten (Scoring) welcher Wagen am besten geeignet ist
        ##Übersetzung
        $translastion_category = array(
            1 => ["'Kombi'", "'SUV'", "'Van'", "'Minibus'"],
            2 => ["'Kleinwagen'", "'Limousine'"],
            3 => ["'Cabrio'", "'Roadster'", "'Sportwagen'", "'Coupé'"],
            4 => ["'Pickup'", "'Van'", "'Minibus'", "'SUV'"],
            5 => ["'Geländewagen'", "'Pickup'", "'SUV'"]
        );
        $sql_category = $translastion_category[$_SESSION["POST"]["nutzart"]];


        $sql_search_recommend = "Select * From dbo.cars where category in (" .
            implode(",", $sql_category) .
            ")";
        #echo $sql_search_recommend;

        ## For DEV purposes
        func_create_html_table($sql_search_recommend,$conn);
    }
}

function func_create_html_table($sql_search_statement,$conn): void
{
    $sql_con = $conn;#sqlsrv_connect($_SESSION["serverName"], $_SESSION["connectionOptions"]);
    $result = sqlsrv_query($sql_con, $sql_search_statement);
    echo "<table>";
    while ($car = sqlsrv_fetch_array($result)) {
        echo "<tr>";
        #Bild des Autos
        #TODO: API Anbindung
        echo "<td>";
        echo "<div class=pictureres>";
        echo "<img src='cars/". $car["brand"] . $car["model"] .".jpg' alt=''>";
        echo "</div>";
        echo "</td>";
        # Eigenschaften des Autos
        echo "<td>";
        echo "Marke: " . $car["brand"] . "<br>";
        echo "Modell: " . $car["model"] . "<br>";
        echo "Preis: " . $car["price"] . " €<br>";
        echo "</td>";
        echo "<td>";
        echo "Erstzulassung: " . $car["registration"]->format('Y') . "<br>";
        echo "Leistung: " . $car["power"] . " PS<br>";
        echo "Kraftstoff: " . $car["fuel"] . "<br>";
        echo "</td>";
        ?>
        <td>
            <button onclick="window.location.href='search.php?id=<?php echo $car["id"] ?>'">Zum Auto</button>
        </td>
        <?php
        echo "</tr>";
    }
    echo "</table>";
}

?>

<html lang="DE">
<head>
    <title>Anmeldung</title>
    <link rel="stylesheet" href="./styles.css">
    <link rel="icon" href="branding/logo_small_icon_only.png">
</head>
<body>
<header>
    <?php include("header.php") ?>
</header>
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
                    <option selected value="%"></option>
                    <?php
                    $sqlstatement = "SELECT DISTINCT brand from cars";
                    $res = sqlsrv_query($conn, $sqlstatement);
                    while ($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)) {
                        echo "<option value =" . $row["brand"] . ">" . $row["brand"] . "</option>";
                    }
                    ?>
                </select>

                <label for="model">Modell</label>
                <select id="model" name="model">
                    <option selected value="%"></option>
                    <?php
                    $sqlstatement = "SELECT DISTINCT model from dbo.cars";
                    $res = sqlsrv_query($conn, $sqlstatement);
                    while ($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)) {
                        echo "<option value =" . $row["model"] . ">" . $row["model"] . "</option>";
                    }
                    ?>
                </select>

                <label for="price">Maximaler Preis</label>
                <select id="price" name="price">
                    <option selected value="1000000"></option>
                    <?php
                    $minstatement = "Select MIN(price) as price FROM dbo.cars";
                    $maxstatement = "Select MAX(price) as price FROM dbo.cars";
                    $minres = sqlsrv_query($conn, $minstatement);
                    $maxres = sqlsrv_query($conn, $maxstatement);
                    $min = round(sqlsrv_fetch_array($minres, SQLSRV_FETCH_ASSOC)["price"], -4);
                    $max = round(sqlsrv_fetch_array($maxres, SQLSRV_FETCH_ASSOC)["price"], -4);
                    for ($price = $min; $price <= $max; $price += 5000) {
                        echo "<option value =" . $price . ">" . $price . "</option>";
                    }
                    ?>
                </select>

                <label for="mileage">Kilometerstand</label>
                <select id="mileage" name="mileage">
                    <option selected value="1000000"></option>
                    <?php
                    $minstatement = "Select MIN(mileage) as mileage FROM CARS";
                    $maxstatement = "Select MAX(mileage) as mileage FROM Cars";
                    $minres = sqlsrv_query($conn, $minstatement);
                    $maxres = sqlsrv_query($conn, $maxstatement);
                    $min = round(sqlsrv_fetch_array($minres, SQLSRV_FETCH_ASSOC)["mileage"], -5);
                    $max = round(sqlsrv_fetch_array($maxres, SQLSRV_FETCH_ASSOC)["mileage"], -5);
                    for ($stand = $min; $stand <= $max; $stand += 5000) {
                        echo "<option value =" . $stand . ">" . $stand . "</option>";
                    }
                    ?>
                </select>
                <input type="hidden" name="suchtyp" id="suchtyp" value="DONE-ML">
                <input type="submit" value="Zur Suche">
            </form>
            <?php
        } else if ($_SESSION["POST"]["suchtyp"] == "KI") {
            ?>
            <form class="search" method="POST" action="search.php?">
                <h1>Wie funktioniert unsere KI?</h1>
                <p> Wir stellen Ihnen nun ein paar Fragen. Ihre Antworten werden anschließend analysiert und Sie
                    bekommen die besten Gebrauchtwagen vorgeschlagen</p>
                <h2>Sind Sie auf der Suche nach einem Fahrzeug für den täglichen Pendelverkehr oder eher für gelegentliche Ausflüge/Wochenendfahrten?</h2>
                <input type="radio" name="usecase" id="pendeln" value="pendeln" checked/><label for="pendeln">Täglicher Pendelverkehr</label>
                <input type="radio" name="usecase" id="gelegentlich" value="gelegentlich"/><label for="gelegentlich">Ausflüge/Wochenendfahrten</label>
                <h2>Wofür wollen Sie das Auto Nutzen?</h2>
                <label for="nutzart"></label>
                <select id="nutzart" name="nutzart">
                    <option value="1">Familienfahrzeug</option>
                    <option value="2">Stadtfahrzeug</option>
                    <option value="3">Freizeit/Sport</option>
                    <option value="4">Arbeitsfahrzeug</option>
                    <option value="5">Abenteuer/Feldarbeit</option>
                </select>
                <h2>
                    Bevorzugen Sie einen sparsamen Kraftstoffverbrauch oder legen Sie Wert auf Leistung und Fahrspaß?
                </h2>
                <input type="radio" name="sparsamkeit" id="consumption" value="consumption" checked/><label for="consumption">Sparsamer Kraftstoffverbrauch</label>
                <input type="radio" name="sparsamkeit" id="power" value="power"/><label for="power">Leistung und Fahrspaß</label>
                <h2>Wie wichtig ist es für Sie, dass die Wartungs- und Reparaturkosten niedrig sind?</h2>
                <label for="lifespan"></label>
                <select id="lifespan" name="lifespan">
                    <option value="1">Sehr wichtig</option>
                    <option value="2">Eher wichtig</option>
                    <option value="3">Vernachlässigbar</option>
                </select>
                <h2>Wieviele Personen sollten mindestens in das Auto passen?</h2>
                <label for="personen"></label>
                <input type="number" min="1" max="9" name="personen" id="personen" value="1">
                <h2>Wieviel wollen Sie maximal für den Wagen ausgeben?</h2>
                <label for="budget"></label>
                <input type="number" min="1000" name="budget" id="budget" value="1000" step="1000">
                <h2>Welche Getriebeart bevorzugen Sie?</h2>
                <input type="radio" name="getriebe" id="auto" value="auto" checked/><label for="auto">Automatik</label>
                <input type="radio" name="getriebe" id="manuel" value="manuel"/><label for="manuel">Schaltgetriebe</label>
                <h2>Wie wichtig ist Ihnen eine hochwertige Innenausstatung?</h2>
                <label for="innenausstatung"></label>
                <select id="innenausstatung" name="innenausstatung">
                    <option value="1">Sehr wichtig - besonders hochwertig</option>
                    <option value="2">Eher wichtig</option>
                    <option value="3">Vernachlässigbar</option>
                </select>
                <h2>Welchen Energieträger soll ihr Auto haben?</h2>
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
                <input type="hidden" name="suchtyp" id="suchtyp" value="DONE-AI">
                <input type="hidden" name="waiting" id="waiting" value="load">
                <h3>Alles gescheckt?</h3>
                <input type="submit" value="Jetzt zum Traumauto">
            </form>
            <?php
        } else if (($_SESSION["POST"]["suchtyp"] == "DONE-ML" || $_SESSION["POST"]["suchtyp"] == "DONE-AI") && $_SESSION["POST"]["waiting"] == "load") {
            $_SESSION["POST"]["waiting"] = "noload";
            header("refresh:5;url=search.php");
            ?>
            <div class="container_animation">
                <div class="progressbar">
                    <span class="loading"></span>
                    <p class="load"><p>Loading...</p>
                </div>
            </div>
            <?php
            } else if ($_SESSION["POST"]["suchtyp"] == "DONE-ML" || $_SESSION["POST"]["suchtyp"] == "DONE-AI"){
            ?>
            <h2>Nicht so voreilig</h2>
            <p>Das Ergebnis der Suche kommt schon noch. Entwicklung braucht eben seine Zeit</p>
            <form method="post">
                <input type="hidden" name="suchtyp" id="suchtyp" value="NEU">
                <input type="submit" value="Neue Suche!">
            </form>
            <?php
            search_AI($conn);
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