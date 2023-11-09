<?php
session_start();

$serverName = "wunschwagen24-dbserver-dev.database.windows.net";
$connectionOptions = array(
    "Database" => "wunschwagen-db-dev",
    "Uid" => "CloudSA1cb8415e",
    "PWD" => "340Uuxwp7Mcxo7Khy"
);
$conn = sqlsrv_connect($serverName, $connectionOptions)

#$res = sqlsrv_query($conn, <statenment>);
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

            <label for="">Modell</label>
            <select id="modell" name="modell">
                <option selected value="*"></option>
                <?php
                $sqlstatement = "SELECT DISTINCT modell from cars where brand = document.querySelector('#brand').value";
                echo $sqlstatement;
                $res = sqlsrv_query($conn, $sqlstatement);
                while( $row = sqlsrv_fetch_array( $res, SQLSRV_FETCH_ASSOC) ) {
                    echo "<option value =".$row["modell"].">".$row["modell"]."</option>";
                }
                ?>
            </select>



        </form>
        
    </div>
</body>
</html>