<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="../css/main.css">
        <link rel="stylesheet" type="text/css" href="../css/registreren.css">
    </head>
    <body>
        <div class="container">

            <div class="header">

                <div class="logo">
                    <img class="logo" src="../plaatjes/logo.png">
                </div>
                <div class="login">
                    <form>
                        <table>
                            <tr><td></td></tr>
                            <tr><td>Gebruikersnaam:</td><td><input class="input"type="text" value="naam"><br></td></tr>
                            <tr><td>Wachtwoord:</td><td><input class="input" type="text" value="wachtwoord"></td></tr>
                            <tr><td><input class="button" type="submit" value="Registreren"></td>
                            <td><input class="button" type="submit" value="Inloggen"></td></tr>
                        </table>
                    </form>
                </div>
            </div>

            <div class="menu">
                <ul class="menu">
                    <li class="menu"><a class="ajax-link" href="userstory6_1.php">Home</a></li>
                    <li class="menu"><a href="#">Papier</a></li>
                    <li class="menu"><a href="#">Dispencers</a></li>
                    <li class="menu"><a href="#">Reinigingsmiddelen</a></li>
                    <li class="menu"><a href="#">Schoonmaakmateriaal</a></li>
                </ul>

            </div>

            <div class="content">

                <div class="navigator">
                    <div class="zoekbalk">

                        <input class="zoekinput" type="text" value="Zoek">
                        <input class="zoeksubmit" type="submit" value="Zoek">

                    </div>

                    <div class="navigatie">

                    </div>
                </div>

                <div class="body" id="main_content">
                    <p class="p">Registreren</p>
                    <table>
                    <form method="post" action="registreerv.php">
                        <tr><td><p class="p">Contactpersoon</p></td></tr>
                        <tr><td>Voornaam:</td><td><input class="input" type="text" value=""></td></tr>
                        <tr><td>Achternaam:</td><td><input class="input" type="text" value=""></td></tr>
                        <tr><td>Telefoonnummer:</td><td><input class="input" type="text" value=""></td></tr>
                        <tr><td>Mobiel:</td><td><input class="input" type="text" value=""></td></tr>
                        
                        <tr><td><p class="p">Bedrijfsgegevens</p></td></tr>
                        <tr><td>Bedrijfsnaam:</td><td><input class="input" type="text" value=""></td></tr>
                        <tr><td>Adres:</td><td><input class="input" type="text" value=""></td></tr>
                        <tr><td>Postcode:</td><td><input class="input" type="text" value=""></td></tr>
                        <tr><td>Plaats:</td><td><input class="input" type="text" value=""></td></tr>
                        <tr><td>KvK-nummer:</td><td><input class="input" type="text" value=""></td></tr>
                        <tr><td>BTW-nummer:</td><td><input class="input" type="text" value=""></td></tr>
                        
                        <tr><td><p class="p">Inloggegevens</p></td></tr>
                        <tr><td>Emailadres:</td><td><input class="input" type="text" value=""></td></tr>
                        <tr><td>Wachtwoord:</td><td><input class="input" type="pwd" value=""></td></tr>
                        <tr><td>Herhaal wachtwoord:</td><td><input class="input" type="pwd" value=""></td></tr>
                        <tr><td colspan=2><input class="button_registreer" type="submit" value="Registreren"</td></tr>
                        
                    </form>
                    </table>
                </div>

                <div class="banner">

                </div>
            </div>

            <div class="footer">

            </div>

        </div>
        <?php
        // put your code here
        ?>
        <script src="http://code.jquery.com/jquery-latest.js"></script>
        <script>
            $(function () {
                $("a.ajax-link").on("click", function (e) {
                    e.preventDefault();
                    $("#main_content").load(this.href);
                });
            });
        </script>
    </body>
</html>

