<?php
//define('THIS_PAGE', 'pagename');
 
$menuitem1 = '<a href="/index.php">Home</a>';
$menuitem2 = '<a href="/papier.php">Papier</a>';
$menuitem3 = '<a href="/dispencers.php">Dispencers</a>';
$menuitem4 = '<a href="/reinigingsmiddelen.php">Reinigingsmiddelen</a>';
$menuitem5 = '<a href="/schoonmaakmateriaal.php">Schoonmaakmateriaal</a>';
$menuitem6 = '<a href="/winkelwagen.php" align="right">( '.countItems(getCookie("winkelmandje")).' ) <img height="20" src="/plaatjes/winkelmandje-menu.png"></a>';
$menuitem7 = '<a href="/administratie/productbeheer.php">Admin</a>';
$menuitem8 = '<a href="/mijnaccount/mijnaccount.php">Mijn account</a>';
 
switch (THIS_PAGE) {
 
case 'Home':
$menuitem1 = '<a style="border: 1px solid #9A9CC5; margin-top:8px;
    color: #fff;
    background-color: #9A9CC5; #9A9CC5;cursor: default;" href="#nogo">Home</a>';
break;
 
case 'Papier':
$menuitem2 = '<a style="border: 1px solid #9A9CC5; margin-top:8px;
    color: #fff;
    background-color: #9A9CC5; cursor: default;" href="#nogo">Papier</a>';
break;
 
case 'Dispencers':
$menuitem3 = '<a style="border: 1px solid #9A9CC5; margin-top:8px;
    color: #fff;
    background-color: #9A9CC5; cursor: default;" href="#nogo">Dispencers</a>';
break;

case 'Reinigingsmiddelen':
$menuitem4 = '<a style="border: 1px solid #9A9CC5; margin-top:8px;
    color: #fff;
    background-color: #9A9CC5; cursor: default;" href="#nogo">Reinigingsmiddelen</a>';
break;

case 'Schoonmaakmateriaal':
$menuitem5 = '<a style="border: 1px solid #9A9CC5; margin-top:8px;
    color: #fff;
    background-color: #9A9CC5; cursor: default;" href="#nogo">Schoonmaakmateriaal</a>';
break;
case 'winkelwagen':
 $menuitem6 =  '<a style="border: 1px solid #9A9CC5; margin-top:8px;
    color: #fff;
    background-color: #9A9CC5; cursor: default" href="#nogo">( '.countItems(getCookie("winkelmandje")).' ) <img height="20px" src="/plaatjes/winkelmandje-menu.png"></a>';
default:
break;
}
?>

<div class="menu">
<?php
print( '<ul class="dropdown">
<li>'.$menuitem1.'</li>
<li>'.$menuitem2.'</li>
<li>'.$menuitem3.'</li>
<li>'.$menuitem4.'</li>
<li>'.$menuitem5.'</li>');
if(validToken($link)){
if(userLevel(getEmail($link), $link) == "Admin") {
print('<li>'.$menuitem7.'<ul>'
        . '<li><a href="/administratie/productbeheer.php">Productbeheer</a></li>'
        . '<li><a href="/administratie/bestellingbeheer.php">Bestellingbeheer</a></li></ul></li>');
} else {
    print('<li>'.$menuitem8.'<ul>'
        . '<li><a href="/mijnaccount/mijngegevens.php">Mijn gegevens</a></li>'
        . '<li><a href="/mijnaccount/mijnbestellingen.php">Mijn bestellingen</a></li>'
        . '<li><a href="/mijnaccount/bestelgeschiedenis.php">Bestelgeschiedenis</a></li></ul></li>');
}
}
print('<li>'.$menuitem6.'</li>');
print('</ul>');
?>
</div>