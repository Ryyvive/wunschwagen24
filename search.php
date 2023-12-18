<?php
session_start();
if(isset($_GET["ai"])){
    header("refresh:2;url=search.php");
}
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
            "Order by Price asc";
        $sql_result_exact = sqlsrv_query($sql_con, $sql_search_exact);
        if ($sql_result_exact && sqlsrv_has_rows($sql_result_exact)) {
            echo "<h1>Ihre Suchergebnisse:</h1>";
            func_create_html_table($sql_search_exact, $conn);
        }else{
            echo "<h1>Basierend auf ihrer Anfrage</h1>";
            #TODO: Empfehlungen
            #IDEE: Erst unwichitge Attrivute wegnehmen - bis modell, niemals typ

            ## For DEV purposes
            $sql_search_recommend = "Select TOP 100 * From dbo.cars";
            func_create_html_table($sql_search_recommend, $conn);
            }
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

        #usecase
        if($_SESSION["POST"]["usecase"] == "pendeln"){
            $usecase = ", mileage asc";
        }else{
            $usecase = "";
        }

        #sparsamkeit
        if($_SESSION["POST"]["sparsamkeit"]=="consumption"){
            $sort = " consumption asc";
        }else{
            $sort = " power desc";
        }
        #lifespan
        if($_SESSION["POST"]["lifespan"]=="1"){
            $lifespan = " brand in ('Honda','BMW')";
        }else if($_SESSION["POST"]["lifespan"]=="2"){
            $lifespan = " brand in ('Volkswagen','BMW')";
        }else{
            $lifespan = "brand LIKE '%'";
        }

        #personen
        $personen = " seats >= ".$_SESSION["POST"]["personen"];

        #budget
        $budget = " price <=  ".$_SESSION["POST"]["budget"];

        #getriebe
        $getriebe = " transmission = '".$_SESSION["POST"]["getriebe"]."'";

        #innenausstattung
        if ($_SESSION["POST"]["innenausstatung"] == "1"){
            $innenausstattung = "registration >= '01-01-2008'";
        }else if($_SESSION["POST"]["innenausstatung"] == "2"){
            $innenausstattung = "registration >= '01-01-2006'";
        }else{
            $innenausstattung = "registration >= '01-01-2000'";
        }

        #Energieträger
        $kraftstof = " fuel in (";
        foreach ($_SESSION["POST"]['fuel'] as $fuel) {
            $kraftstof .= "'".$fuel."', ";
        }
        $kraftstof .= "'None')";

        #power
        $power = " power >= ".$_SESSION["POST"]["leistung"];

        $sql_search_recommend = "Select TOP 100 * From dbo.cars where category in (" .
            implode(",", $sql_category) .
            ") and ".$personen." and ".$budget." and ".$lifespan." and ".$getriebe." and ".$kraftstof." and ".$innenausstattung." and ".$power." Order by ".$sort.$usecase;
        func_create_html_table($sql_search_recommend,$conn);
    }
}

function func_create_html_table($sql_search_statement,$conn): void
{
    $sql_con = $conn;#sqlsrv_connect($_SESSION["serverName"], $_SESSION["connectionOptions"]);
    $result = sqlsrv_query($sql_con, $sql_search_statement);
    if($result){
    echo "<table>";
    while ($car = sqlsrv_fetch_array($result)) {
        echo "<tr>";
        #Bild des Autos
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
}
function safesearch($conn){
    $json =
        '{'.
        '"usecase":"'.$_SESSION["POST"]["usecase"]. '",'.
        '"nutzart":"'.$_SESSION["POST"]["nutzart"]. '",'.
        '"sparsamkeit":"'.$_SESSION["POST"]["sparsamkeit"]. '",'.
        '"lifespan":"'.$_SESSION["POST"]["lifespan"]. '",'.
        '"personen":"'.$_SESSION["POST"]["personen"]. '",'.
        '"budget":"'.$_SESSION["POST"]["budget"]. '",'.
        '"getriebe":"'.$_SESSION["POST"]["getriebe"]. '",'.
        '"innenausstatung":"'.$_SESSION["POST"]["innenausstatung"]. '",'.
        '"fuel":"'.implode(",",$_SESSION["POST"]["fuel"]). '",'.
        '"leistung":"'.$_SESSION["POST"]["leistung"]. '",'.
        '"suchtyp":"'.$_SESSION["POST"]["suchtyp"]. '",'.
        '}';
    $sql_insert = "INSERT INTO search (email, search) VALUES ('".$_SESSION["email"]."','".$json."')";
    sqlsrv_query($conn, $sql_insert);
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
<?php
if (isset($_GET["ai"])) {
?>
<div class="container_animation">
    <div class="progressbar">
        <span class="loading"></span>
        <p class="load"><p>Loading...</p>
    </div>
</div>
<?php
}else{?>
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
                <fieldset class="toggle">
                    <input type="radio" name="suchtyp" id="KI" value="KI" checked/>
                    <label for="KI">Ich möchte mich beraten lassen</label>

                    <input type="radio" name="suchtyp" id="ML" value="ML"/>
                    <label for="ML">Ich weiß, wonach ich suche</label>
                </fieldset>
                <br>
                <input type="submit" value="Auf geht's">
            </form>

            <?php
        } else if ($_SESSION["POST"]["suchtyp"] == "ML") {
            ?>
            <h1> Deutschlands größte Gebrauchtswagen Datenbank</h1>
            <form class="search" method="POST" action="search.php">
                <label for="brand">Marke</label>
                <select id="brand" name="brand" class="custom-dropdown">
                    <option selected value="%"></option>
                    <?php
                    $sqlstatement = "SELECT DISTINCT brand from cars";
                    $res = sqlsrv_query($conn, $sqlstatement);
                    while ($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)) {
                        echo "<option value =" . $row["brand"] . ">" . $row["brand"] . "</option>";
                    }
                    ?>
                </select><br><br>

                <label for="model">Modell</label>
                <select id="model" name="model" class="custom-dropdown">
                    <option selected value="%"></option>
                    <?php
                    $sqlstatement = "SELECT DISTINCT model from dbo.cars";
                    $res = sqlsrv_query($conn, $sqlstatement);
                    while ($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)) {
                        echo "<option value =" . $row["model"] . ">" . $row["model"] . "</option>";
                    }
                    ?>
                </select><br><br>

                <label for="price">Maximaler Preis</label>
                <select id="price" name="price" class="custom-dropdown">
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
                </select><br><br>

                <label for="mileage">Kilometerstand</label>
                <select id="mileage" name="mileage" class="custom-dropdown">
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
                </select><br><br>
                <input type="hidden" name="suchtyp" id="suchtyp" value="DONE-ML">
                <input type="submit" value="Zur Suche">
            </form>
            <?php
        } else if ($_SESSION["POST"]["suchtyp"] == "KI") {
            ?>
            <form class="search" method="POST" action="search.php?ai=load">
                <h1>Wie funktioniert unsere KI?</h1>
                <p> Wir stellen Ihnen nun ein paar Fragen. Ihre Antworten werden anschließend analysiert und Sie
                    bekommen die besten Gebrauchtwagen vorgeschlagen</p>
                <h2>Sind Sie auf der Suche nach einem Fahrzeug für den täglichen Pendelverkehr oder eher für gelegentliche Ausflüge/Wochenendfahrten?</h2>
                <fieldset class="toggle">
                    <input type="radio" name="usecase" id="pendeln" value="pendeln" checked/><label for="pendeln">Täglicher Pendelverkehr</label>
                    <input type="radio" name="usecase" id="gelegentlich" value="gelegentlich"/><label for="gelegentlich">Ausflüge/Wochenendfahrten</label>
                </fieldset>
                <h2>Wofür wollen Sie das Auto Nutzen?</h2>
                <label for="nutzart"></label>
                <select id="nutzart" name="nutzart" class="custom-dropdown">
                    <option value="1">Familienfahrzeug</option>
                    <option value="2">Stadtfahrzeug</option>
                    <option value="3">Freizeit/Sport</option>
                    <option value="4">Arbeitsfahrzeug</option>
                    <option value="5">Abenteuer/Feldarbeit</option>
                </select>
                <h2>
                    Bevorzugen Sie einen sparsamen Kraftstoffverbrauch oder legen Sie Wert auf Leistung und Fahrspaß?
                </h2>
                <fieldset class="toggle">
                    <input type="radio" name="sparsamkeit" id="consumption" value="consumption" checked/><label for="consumption">Sparsamer Kraftstoffverbrauch</label>
                    <input type="radio" name="sparsamkeit" id="power" value="power"/><label for="power">Leistung und Fahrspaß</label>
                </fieldset>
                <h2>Wie wichtig ist es für Sie, dass die Wartungs- und Reparaturkosten niedrig sind?</h2>
                <label for="lifespan"></label>
                <select id="lifespan" name="lifespan" class="custom-dropdown">
                    <option value="1">Sehr wichtig</option>
                    <option value="2">Eher wichtig</option>
                    <option value="3">Vernachlässigbar</option>
                </select>
                <h2>Wieviele Personen sollten mindestens in das Auto passen?</h2>
                <label for="personen"></label>
                <input required type="number" min="1" max="9" name="personen" id="personen" value="3" class="custom-number">
                <h2>Wieviel wollen Sie maximal für den Wagen ausgeben?</h2>
                <input required type="number" min="1000" name="budget" id="budget" value="40000" step="1000" class="custom-number">
                <label for="budget">€</label>
                <h2>Welche Getriebeart bevorzugen Sie?</h2>
                <fieldset class="toggle">
                    <input type="radio" name="getriebe" id="auto" value="Automatik" checked/><label for="auto">Automatik</label>
                    <input type="radio" name="getriebe" id="manuel" value="Schaltgetriebe"/><label for="manuel">Schaltgetriebe</label>
                </fieldset>
                <h2>Wie wichtig ist Ihnen eine hochwertige Innenausstatung?</h2>
                <label for="innenausstatung"></label>
                <select id="innenausstatung" name="innenausstatung" class="custom-dropdown">
                    <option value="1">Sehr wichtig - besonders hochwertig</option>
                    <option value="2">Eher wichtig</option>
                    <option value="3">Vernachlässigbar</option>
                </select>
                <h2>Welchen Energieträger soll ihr Auto haben?</h2>
                <section class="app">
                    <article class="custom-checkbox">
                        <input type="checkbox" id="fuel[]" name="fuel[]" value="Autogas" checked>
                        <div>
                            <span>Autogas</span>
                        </div>
                    </article><br>

                    <article class="custom-checkbox">
                        <input type="checkbox" id="fuel[]" name="fuel[]" value="Diesel" checked>
                        <div>
                            <span>Diesel</span>
                        </div>
                    </article><br>

                    <article class="custom-checkbox">
                        <input type="checkbox" id="fuel[]" name="fuel[]" value="Benzin" checked>
                        <div>
                            <span>Benzin</span>
                        </div>
                    </article><br>

                    <article class="custom-checkbox">
                        <input type="checkbox" id="fuel[]" name="fuel[]" value="Elektrisch" checked>
                        <div>
                            <span>Elektrisch</span>
                        </div>
                    </article><br>
                </section>
                <h2>Benötige Leistung</h2>
                <input type="number" name="leistung" id="leistung" max="300" class="custom-number">
                <label for="leistung">PS</label>
                <input type="hidden" name="suchtyp" id="suchtyp" value="DONE-AI">
                <br><br>
                <h3>Alles gecheckt?</h3>
                <input type="submit" value="Jetzt zum Traumauto">
            </form>
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
            if (isset($_SESSION["email"])){
            ?>
                <button onclick="<?php echo safesearch($conn);?>">Suche Speichern</button>
            <?php
            }
            search_AI($conn);
        }
    } else {
        ?>
        <button onclick="window.location.href='search.php?'">Zurück zur Suche</button>
        <?php
        $sql_onecar = "Select * FROM dbo.cars where id = ".$_GET["id"];
        $result_onecar = sqlsrv_query($conn, $sql_onecar);
        if($result_onecar){
            echo "<table>";
            while ($car = sqlsrv_fetch_array($result_onecar)) {
                echo "<tr>";
                #Bild des Autos
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
                echo "Erstzulassung: " . $car["registration"]->format('Y') . "<br>";
                echo "Leistung: " . $car["power"] . " PS<br>";
                echo "Kraftstoff: " . $car["fuel"] . "<br>";
                echo "Kilometerstand: ".$car["mileage"]. " km<br>";
                echo "Getriebe: ".$car["transmission"]."<br>";
                echo "Kategorie: ".$car["category"]."<br>";
                echo "Sitze: ".$car["seats"]."<br>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        ?>
        <h1>Inen gefällt was Sie sehen?</h1>
        <p> Setzen Sie sich hier mit unserem Team in Kontakt</p>
        <form method = "POST" action="search.php">
            <label for="kontaktmail">Kontakadresse</label>
            <input type ="email" name = "kontaktmail" value = "<?php if(isset($_SESSION["email"])){echo $_SESSION["email"];} ?>">
            <label for="beschreibung">Beschreibung</label>
            <input type="text" name="beschreibung">
            <input type="submit">
        </form>
    <?php
    }
    ?>
</div>
<?php
}
?>
</body>
</html>