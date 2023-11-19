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
                if(isset($_SESSION["vorname"])){
                    echo "<a>Willkommen ".$_SESSION["vorname"]."</a>";
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