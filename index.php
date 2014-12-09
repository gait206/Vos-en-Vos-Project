<?php
session_start();
include('functies.php');
$link = connectDB();
$cookiename = 'winkelmandje';
if (!existCookie($cookiename)) {
    addCookie($cookiename, array());
	}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    </head>
    <body>
        <div class="container">

            <div class="header">

                <div class="logo">
                    <img class="logo" src="plaatjes/logo.png">
                </div>
                <div class="login">
                    <?php
                    include('login/loginscherm.php');
                    ?>
                </div>
            </div>

            <?php
			define('THIS_PAGE', 'Home');
			include('menu.php');
			?>

            <div class="content" id="main_content">
                <?php include("administratie/productenblock.php"); 
                        ?>
                
            </div>

            <div class="footer">
            <table class="footertable" border=0 width="100%">
            <tr class="footerheader" height="30" >
            <td>Vos en Vos Tissue</td>
            <td>Contact</td> 
            <td>Bedrijfgegevens</td>
            <td>Follow me</td>
            </tr><tr>
            
            <td>Conradstraat 2E</td>
            <td class="tablelink">Email: <a href="mailto:info@vos-vos.nl">info@vos-vos.nl</a></td>
            <td>BTW nr.: NL xxxx.xx.xxx.xxx</td>
     		<td class="tablelink"><a href="https://www.facebook.com/vosenvos.schoonmaakbedrijf" target="_blank">Volg ons op facebook</td>
            </tr><tr>
            
            <td>8004 DA  Zwolle</td>
            <td>Telefoonnummer: 038-4529329</td> 
            <td>KvK nr.: xxxxxxxx</td>
            </tr><tr>
            
            </tr>
            
            </table>
            </div>
        </div>
    </body>
</html>
<?php
mysqli_close($link);
?>