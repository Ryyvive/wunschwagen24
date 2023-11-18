<?php
session_start();

$serverName = "wunschwagen24-dbserver-dev.database.windows.net";
$connectionOptions = array(
    "Database" => "wunschwagen-db-dev",
    "Uid" => "CloudSA1cb8415e",
    "PWD" => "340Uuxwp7Mcxo7Khy"
);
$conn = sqlsrv_connect($serverName, $connectionOptions);

function search_AI(){
    $input_variable = array(
            "brand" => $_POST["brand"],
            "modell" => $_POST["modell"]

    );

    ## auf Suche Exact zutreffen
    #TODO: Komplexe SQL Abfrage zum Suchen von genau passenden Angeboten

    ## AI zum Heraussuchen des am besten passenden Wagens
    #TODO: Bewertungsalgorithmus zum Bewerten (Scoring) welcher Wagen am besten geeignet ist
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
        if (!isset($_GET["suchtyp"])){
            ?>
            <form name="suchtyp-einstellung" method="get">
                <h1>Wie hätten Sie es gerne?</h1>
                <p>Unsere Entwickler sind auf dem neusten Stand der Technik und bieten ihnen mit KI die Möglichkeit über ein paar Fragen das perfekte Auto zur Verfügung zu stellen.</p>
                <h2>Sie wissen schon, was Sie suchen?</h2>
                <p>Auch das ist kein Problem. Mit einfachen Filtern ermöglichen wir Ihnen Zugriff auf Deutschlands größte Gebrauchtwagendatenbank.</p>
                <input type="radio" name="suchtyp" id="KI" value="KI" checked /><label for="KI">Ich möchte mich beraten lassen</label>
                <input type="radio" name="suchtyp" id="ML" value="ML" /><label for="ML">Ich weiß, wonach ich suche</label>
                <br>
                <input type="submit" value="Auf geht's">
            </form>

        <?php
        }else if($_GET["suchtyp"]=="ML"){
        ?>
            <h1> Deutschlands größte Gebrauchtswagen Datenbank</h1>
            <form class = "search" method = "POST" action="search.php">
                <label for="brand">Marke</label>
                <select id="brand" name="brand">
                    <option selected value="*"></option>
                    <?php
                    $sqlstatement = "SELECT DISTINCT brand from cars";
                    $res = sqlsrv_query($conn, $sqlstatement);
                    while( $row = sqlsrv_fetch_array( $res, SQLSRV_FETCH_ASSOC) ) {
                        echo "<option value =".$row["brand"].">".$row["brand"]."</option>";
                    }
                    ?>
                </select>

                <label for="modell">Modell</label>
                <select id="modell" name="modell">
                    <option selected value="*"></option>
                    <?php
                    $sqlstatement = "SELECT DISTINCT modell from cars";
                    $res = sqlsrv_query($conn, $sqlstatement);
                    while( $row = sqlsrv_fetch_array( $res, SQLSRV_FETCH_ASSOC) ) {
                        echo "<option value =".$row["modell"].">".$row["modell"]."</option>";
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
                    $min = round(sqlsrv_fetch_array($minres, SQLSRV_FETCH_ASSOC)["Preis"],-5);
                    $max = round(sqlsrv_fetch_array($maxres, SQLSRV_FETCH_ASSOC)["Preis"],-5);
                    for ($price = $min; $price <= $max; $price += 5000 ){
                    echo "<option value =".$price.">".$price."</option>";
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
                    $min = round(sqlsrv_fetch_array($minres, SQLSRV_FETCH_ASSOC)["kilometerstand"],-5);
                    $max = round(sqlsrv_fetch_array($maxres, SQLSRV_FETCH_ASSOC)["kilometerstand"],-5);
                    for ($stand = $min; $stand <= $max; $stand += 5000 ){
                        echo "<option value =".$stand.">".$stand."</option>";
                    }
                    ?>
                </select>
            </form>
        <?php
        }else if($_GET["suchtyp"]=="KI"){
            ?>
            <form class = "search" method = "POST" action="search.php">
                <h1>Wie funktioniert unsere KI?</h1>
                <p> Wir stellen Ihnen nun ein paar Fragen. Ihre Antworten werden anschließend analysiert und Sie bekommen die besten Gebrauchtwagen vorgeschlagen</p>
                <h2>Wofür wollen Sie das Auto Nutzen?</h2>
                <label for="nutzart"></label>
                <select id="nutzart" name="nutzart">
                    <option value ="1">Familienfahrzeug</option>
                    <option value ="2">Pendeln</option>
                    <option value ="3">Wochenendfahrten</option>
                    <option value ="4">Saisonfahren</option>
                </select>
                <h2>Wieviele Personen sollten mindestens in das Auto passen?</h2>
                <label for="personen"></label>
                <input type="number" min="1" max="9" name="personen" id="personen">
                <h2>Wieviel wollen Sie maximal für den Wagen ausgeben?</h2>
                <label for="budget"></label>
                <input type="number" min="1000"  name="budget" id="budget">
                <h2>Welche Getriebeart bevorzugen Sie?</h2>
                <input type="radio" name="getriebe" id="getriebe" value="auto" checked /><label for="getriebe">Automatik</label>
                <input type="radio" name="getriebe" id="getriebe" value="manuel" /><label for="getriebe">Schaltgetriebe</label>
            </form>
        <?php
        }
        ?>
    </div>
</body>
</html>