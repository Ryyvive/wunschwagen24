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
    if(isset($_SESSION["POST"]["newcar"])&&$_SESSION["POST"]["newcar"]=="new"){
        echo "Der geschätzte Preis für ihren Neuwagen beträgt <br>";
        echo newcar_ml()." €";
        ?>
        <form method = 'post' action = 'sell.php'>
            <input type="hidden" id="newcar" name = "newcar" value="submit">
            <input type='submit'>
        </form>
    <?php
    } else if(isset($_SESSION["POST"]["newcar"])){
        echo "Ihre Angaben wurden an uns gesendet. Wir werden uns in Kürze mit ihnen in Verbindung setzen";
    }
    ?>
    <form method="post" action="sell.php?loading=ml">
        <label for="brand">Marke</label>
        <select required id = "brand" name = "brand">
            <option selected value="BMW">BMW</option>
            <option selected value="KIA">KIA</option>
            <option selected value="Volkswagen">VW</option>
        </select>
        <label for="model">Modell</label>
        <input required type="text" id = "model">
        <label for="mileage">Kilometerstand</label>
        <input required type="number" id = mileage>
        <label for="registration-year">Erstzulassung</label>
        <input required type = "number" id="registration-year">
        <label for="power">PS</label>
        <input required type="number" id = "power">
        <label for="transmission">Getriebe</label>
        <select id = "transmission" name = "transmission">
            <option selected value="Automatik">Automatik</option>
            <option value = "Schaltgetriebe">Schaltgetriebe</option>
        </select>
        <label for = "owners">Vorbesitzer</label>
        <input required type="number" id="owners">
        <label for ="ccm">Hubraum</label>
        <input required type="number" id="ccm">
        <label for = "pictures">Fotos vom Auto</label>
        <input required type="file" id = "pictures" accept=".jpeg,.png,.jpg">
        <label for = "condition">Zustand des Wagens</label>
        <select required id="condition">
            <option selected value = "1">Sehr gut/Scheckheft</option>
            <option value = "2">Gepflegt</option>
            <option value = "3">Gebrauchsspuren</option>
            <option value = "4">Unfallwagen</option>
        </select>
        <input type="hidden" name = "newcar" id="newcar" value= "new">
        <input type="submit">

    </form>
</div>
<?php
        }
            ?>