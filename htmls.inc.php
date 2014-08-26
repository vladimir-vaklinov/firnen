<?php

$GLOBALS['html']='<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>'.$GLOBALS['pageTitle'].'</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<meta name="keywords" content="'.$GLOBALS['pageKeywords'].'" />
<meta name="description" content="'.$GLOBALS['pageDescription'].'" />
<link rel="icon" href="/assets/img/favicon.png" type="image/x-icon" />
<link rel="shortcut icon" href="/assets/img/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="/assets/css/main.css" type="text/css" media="all" />
<link rel="stylesheet" href="/assets/css/signup.css" type="text/css" media="all" />
<link rel="stylesheet" href="/assets/css/myalbums.css" type="text/css" media="all" />
<link rel="stylesheet" href="/assets/css/profile.css" type="text/css" media="all" />
<link rel="stylesheet" href="/assets/css/public.css" type="text/css" media="all" />
<link rel="stylesheet" href="/assets/css/responsive.css" type="text/css" media="all" />
<link rel="stylesheet" href="/assets/css/print.css" type="text/css" media="print" />
</head>
<body>
<div id="wrapper">
	<header>
		<div id="logoarea">
			<a href="/"><img src="/assets/img/firnen-logo.png" alt="FIRNEN Logo" /></a>
		</div>
		<div id="header-area-right">
			<nav id="menu">
				<ul id="nav-menu">'.$GLOBALS['navlinks'].'</ul>			
			</nav>
			<div id="search-form">
				<form method="post" action="/search/">
					<input type="text" name="search" placeholder="Search Photos / Albums" value="'.$GLOBALS['searchstr'].'" required="required" maxlength="150"/>
					<button type="submit" name="submit"></button>
				</form>
			</div>
		</div>
	</header>

	<section class="contentblock">
		'.$GLOBALS['contentblock'].'
	</section>

	<footer>
		<div id="copyright">
		<p>				
			All rights reserved &copy; '.date('Y').' &middot;
			<a href="http://softuni.bg" target="_blank">SoftUni</a> team FIRNEN &middot;
			<a href="http://validator.w3.org/check?verbose=1&amp;uri=http%3A%2F%2Ffirnen.info%2F" target="_blank">HTML5</a> &amp;
			<a href="http://jigsaw.w3.org/css-validator/validator?profile=css3&amp;warning=0&amp;uri=http%3A%2F%2Ffirnen.info%2F" target="_blank">CSS3</a> validated
		</p>
		</div>
		<div id="media">
            <a href="https://www.facebook.com/sharer/sharer.php?u=http://firnen.info/" target="_blank" class="facebook"></a>
            <a href="https://plus.google.com/share?url=http://firnen.info/" target="_blank" class="googleplus"></a>
            <a href="https://twitter.com/home?status=http://firnen.info/" target="_blank" class="twitter"></a>
        </div>
	</footer>
</div>
</body>
</html>
';
