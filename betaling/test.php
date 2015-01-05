<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        // standaard veilige encryptie met een globale key
        $size = 20;
        $key = base64_encode(mcrypt_create_iv($size));
        $string = "oke";
        
        print('Key: '.$key);
        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_ECB));
        print('<br>Encrypted: '.$encrypted);
        print('<br>Aantal tekens: '. strlen($encrypted));
        $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($encrypted), MCRYPT_MODE_ECB);
        print('<br>Uncrypted: '.$decrypted);
        ?>
    </body>
</html>
