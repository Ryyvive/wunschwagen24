<?php
session_start();
if(isset($_POST["newcar"])){
    $_SESSION["POST"] = $_POST;
}
if(isset($_GET["loading"])){
    header("refresh:2;url=sell.php");
}
unset($_POST);
function newcar_ml()
{
    /*This ML Algorithm calculates the price for a new Car Uploaded
     *
     * Args:
     * Returns:
    */
    $inital_model = array(
            "baseprice" => 20000*(rand(1100,1990)/1000),
            "phase" => 0,
            "cars" => 1
    );
    #Brand Input into the ML
    $brandvalue = array(
            "BMW" => 5,
            "Audi" => 4,
            "Volkswagen" => 3,
            "KIA" => 3
    );
    $inital_model["baseprice"] += 1000*$brandvalue[$_SESSION["POST"]["brand"]];
    # Including mileage
    $inital_model["baseprice"] += (200000-$_SESSION["POST"]["mileage"])/100;
    #condition
    $inital_model["baseprice"] += 5-$_SESSION["POST"]["condition"]*1000;
    #Owners
    $inital_model["baseprice"] -= $_SESSION["POST"]["owners"]*1000;

    return $inital_model["baseprice"];
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

    <?php
        if(isset($_GET["loading"])){
            ?>
            <div class="container_animation">
                <div class="progressbar">
                    <span class="loading"></span>
                    <p class="load"><p>Loading...</p>
                </div>
            </div>
                <?php
        }else{
    ?>
            <div class="main-content">
            <h1>Ihr Auto liegt ihnen am Herzen?</h1>
            <p>Uns auch! - Deswegen stellen wir sicher, dass ihr Auto in die richtigen Hände kommt.</p>
                <?php
                if (isset($_SESSION["POST"]["newcar"]) && $_SESSION["POST"]["newcar"] == "new") {
                    echo "<div class='price-info'>Der geschätzte Preis für ihren Neuwagen beträgt <br>";
                    echo "<span class='price-value'>" . newcar_ml() . " €</span>";
                    echo "</div>";
                ?>
                <form method = 'post' action = 'sell.php'>
                    <input type="hidden" id="newcar" name = "newcar" value="submit">
                    <input type='submit'>
                </form><br>
            <?php
            } else if(isset($_SESSION["POST"]["newcar"])){
                echo "<div class='sell-send'>Ihre Angaben wurden an uns gesendet. Wir werden uns in Kürze mit ihnen in Verbindung setzen</div>";
            }
            ?>
            <br><br>
            <form method="post" action="sell.php?loading=ml">
                <label for="brand">Marke</label>
                <select required id = "brand" name = "brand" class="custom-dropdown">
                    <option selected value="BMW">BMW</option>
                    <option value="KIA">KIA</option>
                    <option value="Volkswagen">VW</option>
                </select><br><br>
                <label for="model">Modell</label>
                <input required type="text" id = "model" name="model" class="custom-number"><br><br>
                <label for="mileage">Kilometerstand</label>
                <input required type="number" id = mileage name = "mileage" max ="199999" class="custom-number"><br><br>
                <label for="registration-year">Erstzulassung</label>
                <input required type = "number" id="registration-year" name="registration-year" max="2023" class="custom-number"><br><br>
                <label for="power">PS</label>
                <input required type="number" id = "power" name="power" class="custom-number"><br><br>
                <label for="transmission">Getriebe</label>
                <select id = "transmission" name = "transmission" class="custom-dropdown">
                    <option selected value="Automatik">Automatik</option>
                    <option value = "Schaltgetriebe">Schaltgetriebe</option>
                </select><br><br>
                <label for="ccm">Hubraum</label>
                <input required type="number" id="ccm" name ="ccm" class="custom-number"><br><br>
                <label for="owners">Anzahl Vorbesitzer</label>
                <input required type="number" id="owners" name ="owners" class="custom-number"><br><br>
                <label for="condition">Zustand</label>
                <select required id="condition" name="condition" class="custom-dropdown">
                    <option selected value = "1">Sehr gut/Scheckheft</option>
                    <option value = "2">Gepflegt</option>
                    <option value = "3">Gebrauchsspuren</option>
                    <option value = "4">Unfallwagen</option>
                </select><br><br>
                <label for="condition">Bild</label>
                <label class="custom-file-upload">
                    <input required type="file" accept=".jpeg,.png,.jpg" id="file-upload" style="display: none;">
                    <span>Wähle eine Datei aus</span>
                </label>
                <div id="file-name"></div>
                <br><br>
                <input type="hidden" name = "newcar" id="newcar" value= "new">
                <input type="submit" value="Preis berechnen">
            </form><br>
        </div>
    <footer class="site-footer">
        <?php include("footer.php") ?>
    </footer>
    <script>
        window.onload = function() {
            var fileInput = document.getElementById('file-upload');

            fileInput.onchange = function() {
                if (fileInput.files.length > 0) {
                    var fileName = fileInput.files[0].name;
                    document.getElementById('file-name').textContent = 'Ausgewählte Datei: ' + fileName;
                }
            };
        };
    </script>
</body>
<?php
        }
            ?>