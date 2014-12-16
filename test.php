<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>jQuery Hover Effect</title>
<script type='text/javascript' src='http://code.jquery.com/jquery-2.1.0.min.js'></script>
<script type='text/javascript'>
$(document).ready(function(){
	$(".button").hover(function() {
		$(this).attr("src","/plaatjes/winkelmandje-menu-thispage.png");
			}, function() {
		$(this).attr("src","/plaatjes/winkelmandje-menu.png");
	});
});
</script>
</head>

<body bgcolor="#000000">
<img src="/plaatjes/winkelmandje-menu.png" alt="My button" class="button" />
</body>
</html>