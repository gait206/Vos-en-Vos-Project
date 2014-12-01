
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

            <?php
			define('THIS_PAGE', 'Papier');
			include('menu.php');
			?>

            <div class="content" id="main_content">
                <?php include("administratie/producten.php"); ?>
                
            </div>

            <div class="footer">

            </div>

        </div>
        <?php
        // put your code here
        ?>
        
    </body>
</html>
