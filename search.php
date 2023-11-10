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

            <label for="price">Preis</label>
            <?php
            $minstatement = "Select MIN(Preis) as Preis FROM CARS";
            $maxstatement = "Select MAX(Preis) as Preis FROM Cars";
            $minres = sqlsrv_query($conn, $minstatement);
            $maxres = sqlsrv_query($conn, $maxstatement);
            $min = sqlsrv_fetch_array($minres, SQLSRV_FETCH_ASSOC)["Preis"];
            $max = sqlsrv_fetch_array($maxres, SQLSRV_FETCH_ASSOC)["Preis"];
            ?>
            <input type="range" id="price" name="price" min="<?php  echo $min; ?>" max = "<?php  echo $max; ?>">




        </form>
        
    </div>
</body>
</html>