<head>
<script type='text/javascript' src='http://code.jquery.com/jquery-2.1.0.min.js'></script>
<script type='text/javascript'>
$(document).ready(function(){
	$(".button2").hover(function() {
		$(this).children('img').attr("src","/plaatjes/winkelmandje-menu-thispage.png");
			}, function() {
		$(this).children('img').attr("src","/plaatjes/winkelmandje-menu.png");
	});
});
</script>
</head>
<?php
//define('THIS_PAGE', 'pagename');
 
$menuitem1 = '<a href="/index.php">Home</a>';
$menuitem2 = '<a href="/papier.php">Papier</a>';
$menuitem3 = '<a href="/dispensers.php">Dispensers</a>';
$menuitem4 = '<a href="/reinigingsmiddelen.php">Reinigingsmiddelen</a>';
$menuitem5 = '<a href="/schoonmaakmateriaal.php">Schoonmaakmateriaal</a>';
$menuitem6 = '<a href="/winkelwagen.php" class="button2">( '.countItems(getCookie("winkelmandje")).' ) 
<img height="20px" src="/plaatjes/winkelmandje-menu.png"></a>';
$menuitem7 = '<a href="/administratie/productbeheer.php">Admin</a>';
$menuitem8 = '<a href="./mijnaccount/mijnaccount.php">Mijn account</a>';
 
switch (THIS_PAGE) {
 
case 'Home':
$menuitem1 = '<a style="//border: 1px solid #9A9CC5; margin-top:8px;
    color: #3CF;
    //background-color:#9A9CC5; cursor: default;" href="#nogo">Home</a></li>';
break;
 
case 'Papier':
$menuitem2 = '<a style="//border: 1px solid #9A9CC5; margin-top:8px;
    color: #3CF;
    //background-color: #9A9CC5; cursor: default;" href="#nogo">Papier</a>';
break;
 
case 'Dispensers':
$menuitem3 = '<a style="//border: 1px solid #9A9CC5; margin-top:8px;
    color: #3CF;
    //background-color: #9A9CC5; cursor: default;" href="#nogo">Dispensers</a>';
break;

case 'Reinigingsmiddelen':
$menuitem4 = '<a style="//border: 1px solid #9A9CC5; margin-top:8px;
    color: #3CF;
    //background-color: #9A9CC5; cursor: default;" href="#nogo">Reinigingsmiddelen</a>';
break;

case 'Schoonmaakmateriaal':
$menuitem5 = '<a style="//border: 1px solid #9A9CC5; margin-top:8px;
    color: #3CF;
    //background-color: #9A9CC5; cursor: default;" href="#nogo">Schoonmaakmateriaal</a>';
break;
case 'winkelwagen':
 $menuitem6 =  '<a style="//border: 1px solid #9A9CC5; margin-top:8px;
    color: #3CF;
    //background-color: #9A9CC5; cursor: default" href="#nogo">( '.countItems(getCookie("winkelmandje")).' ) <img height="20px" src="/plaatjes/winkelmandje-menu-thispage.png"></a>';
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
if(userLevel(getKlantnr($link), $link) == "Admin") {
print('<li>'.$menuitem7.'<ul>'
        . '<li style="width:auto; border-right:none;"><a href="/administratie/productbeheer.php">Productbeheer</a></li>'
        . '<li style="width:auto; border-right:none;"><a href="/administratie/bestellingbeheer.php">Bestellingbeheer</a></li>'
		. '<li style="width:auto; border-right:none;"><a href="/administratie/accountbeheer.php">Accountbeheer</a></li></ul></li>');
} else {
    print('<li>'.$menuitem8.'<ul>'
        . '<li style="width:auto; border-right:none;"><a href="/mijnaccount/mijngegevens.php">Mijn gegevens</a></li>'
        . '<li style="width:auto; border-right:none;"><a href="/mijnaccount/mijnbestellingen.php">Mijn bestellingen</a></li>'
        . '<li style="width:auto; border-right:none;"><a href="/mijnaccount/bestelgeschiedenis.php">Bestelgeschiedenis</a></li></ul></li>');
}
}
print('<li style="border-right:none;">'.$menuitem6.'</li>');
print('</ul>');
?>
</div>