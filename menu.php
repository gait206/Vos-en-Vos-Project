<?php
//define('THIS_PAGE', 'pagename');
 
$menuitem1 = '<a href="/index.php">Home</a>';
$menuitem2 = '<a href="/papier.php">Papier</a>';
$menuitem3 = '<a href="/dispencers.php">Dispencers</a>';
$menuitem4 = '<a href="/reinigingsmiddelen.php">Reinigingsmiddelen</a>';
$menuitem5 = '<a href="/schoonmaakmateriaal.php">Schoonmaakmateriaal</a>';
$menuitem6 = '<a href="/winkelwagen.php">winkelwagen</a>';
 
switch (THIS_PAGE) {
 
case 'Home':
$menuitem1 = '<a style="border: 1px solid #9A9CC5;
    color: #fff;
    background-color: #9A9CC5; #9A9CC5;cursor: default;" href="#nogo">Home</a>';
break;
 
case 'Papier':
$menuitem2 = '<a style="border: 1px solid #9A9CC5;
    color: #fff;
    background-color: #9A9CC5; cursor: default;" href="#nogo">Papier</a>';
break;
 
case 'Dispencers':
$menuitem3 = '<a style="border: 1px solid #9A9CC5;
    color: #fff;
    background-color: #9A9CC5; cursor: default;" href="#nogo">Dispencers</a>';
break;

case 'Reinigingsmiddelen':
$menuitem4 = '<a style="border: 1px solid #9A9CC5;
    color: #fff;
    background-color: #9A9CC5; cursor: default;" href="#nogo">Reinigingsmiddelen</a>';
break;

case 'Schoonmaakmateriaal':
$menuitem5 = '<a style="border: 1px solid #9A9CC5;
    color: #fff;
    background-color: #9A9CC5; cursor: default;" href="#nogo">Schoonmaakmateriaal</a>';
break;
case 'winkelwagen':
 $menuitem6 =  '<a style="border: 1px solid #9A9CC5;
    color: #fff;
    background-color: #9A9CC5; cursor: default;" href="#nogo">winkelwagen</a>';
default:
break;
}
?>

<div class="menu">
<?php
echo '<ul>
<li>'.$menuitem1.'</li>
<li>'.$menuitem2.'</li>
<li>'.$menuitem3.'</li>
<li>'.$menuitem4.'</li>
<li>'.$menuitem5.'</li>
<li>'.$menuitem6.'</li>
</ul>';
?>
</div>