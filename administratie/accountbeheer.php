<?php
session_start();
include('../functies.php');
$link = connectDB();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="../css/main.css">
        <link rel="stylesheet" type="text/css" href="../css/admin.css">
        <link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <div class="container">

            <div class="header">

                <div class="logo">
                    <img class="logo" src="../plaatjes/logo.png">
                </div>
                <div class="login">
                    <?php
                    include('../login/loginscherm.php');
                    ?>
                </div>
            </div>

            <?php
			define('THIS_PAGE', 'Productbeheer');
			include('../menu.php');
			?>

            <div class="content">
                <script>
                    function checkDelete(){
                        return confirm("Weet u zeker dat u dit product wilt verwijderen?");
                    }
                </script>
                <div class="body" id="main_content">
                    <?php
                    restrictedPage("Admin", $link);
					
					$result = mysqli_query($link, 'SELECT * FROM product');
                    $row = mysqli_fetch_assoc($result);
                    
                    ?>

                </div>

                
            </div>

            <div class="footer">

            </div>

        </div>
        
    </body>
</html>
<?php
            mysqli_close($link);
            ?>
