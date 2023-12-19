<?php
session_start();
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
<div class="main-content">
    <div class="teaminfo">
        <h1>Möchten Sie zusätzlich zur KI noch weitere Expertise? Lassen Sie sich von unseren Experten beraten!</h1><br>
        <table>
            <tr>
                <td>
                    <img src="team/Kunze.jpeg" alt="">
                </td>
                <td>
                    <span class="highlight-name">Herr Kunze</span>
                    ist Berater bei uns seit
                    <span class="highlight-year">2023</span>
                </td>
            </tr>
            <tr>
                <td>
                    <img src="team/Fey.jpg" alt="">
                </td>
                <td>
                    <span class="highlight-name">Herr Fey</span>
                    ist Berater bei uns seit <br>
                    <span class="highlight-year">2023</span>
                </td>
            </tr>
            <tr>
                <td>
                    <img src="team/Barz.jpeg" alt="">
                </td>
                <td>
                    <span class="highlight-name">Herr Barz</span>
                    ist Berater bei uns seit <br>
                    <span class="highlight-year">2023</span>
                </td>
            </tr>
            <tr>
                <td>
                    <img src="team/Hartemann.jpeg" alt="">
                </td>
                <td>
                    <span class="highlight-name">Herr Hartemann</span>
                    ist Berater bei uns seit <br>
                    <span class="highlight-year">2023</span>
                </td>
            </tr>
            <tr>
                <td>
                    <img src="team/Marzusch.jpg" alt="">
                </td>
                <td>
                    <span class="highlight-name">Herr Marzusch</span>
                    ist Berater bei uns seit <br>
                    <span class="highlight-year">2023</span>
                </td>
            </tr>
        </table><br><br>
    </div>
    <div class="contact-form">
        <h2>Termin vereinbaren</h2>
        <form method="post" action="consulting.php">
            <label for="berater">Wähle einen Berater:</label>
            <select name="berater" id="berater">
                <option value="kunze">Herr Kunze</option>
                <option value="fey">Herr Fey</option>
                <option value="barz">Herr Barz</option>
                <option value="hartemann">Herr Hartemann</option>
                <option value="marzusch">Herr Marzusch</option>
            </select>

            <label for="name">Dein Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Deine E-Mail:</label>
            <input type="email" id="email" name="email" required>

            <label for="date">Wunschtermin:</label>
            <input type="date" id="date" name="date" required>

            <label for="message">Nachricht:</label>
            <textarea id="message" name="message"></textarea>

            <input type="submit" value="Termin anfragen">
        </form>
    </div>
</div>
<footer class="site-footer">
    <?php include("footer.php") ?>
</footer>
</body>
</html>
