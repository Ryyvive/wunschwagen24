<div class="topnav">
    <table>
        <tbody>
        <tr>
            <th>
                <div class="backimg">
                <a href="index.php" class="backimg">
                    <img src="branding/logo_large.png" alt="">
                </a>
                </div>
            </th>
            <th>
                <a href="search.php">Suchen</a>
                <a href="sell.php">Verkaufen</a>
                <a href="consulting.php">Beratung</a>
            </th>
            <th>
                <?php
                if(isset($_SESSION["vorname"])){
                    echo "<a href = profile.php>Willkommen ".$_SESSION["vorname"]."</a>";
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