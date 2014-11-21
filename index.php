
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="css/main.css">
    </head>
    <body>
        <div class="container">

            <div class="header">

                <div class="logo">
                    <img class="logo" src="plaatjes/logo.png">
                </div>
                <div class="login">
                    <form>
                        <table>
                            <tr><td></td></tr>
                            <tr><td>Gebruikersnaam:</td><td><input class="gebruikersnaam"type="text" value="naam"><br></td></tr>
                            <tr><td>Wachtwoord:</td><td><input class="wachtwoord" type="text" value="wachtwoord"></td></tr>
                            <tr><td><input type="submit" value="login"></td></tr>
                        </table>
                    </form>
                </div>
            </div>

            <div class="menu">
                <ul class="menu">
                    <li class="menu"><a class="ajax-link" href="administratie/producten.php">Home</a></li>
                    <li class="menu"><a href="#">Papier</a></li>
                    <li class="menu"><a href="#">Dispencers</a></li>
                    <li class="menu"><a href="#">Reinigingsmiddelen</a></li>
                    <li class="menu"><a href="#">Schoonmaakmateriaal</a></li>
                </ul>

            </div>

            <div class="content" id="main_content">
                <?php include("administratie/producten.php"); ?>
                
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
