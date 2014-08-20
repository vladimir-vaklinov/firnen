<?php

$GLOBALS['html']='<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>'.trim($GLOBALS['pageTitle']).'</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<link rel="icon" href="/assets/img/favicon.png" type="image/x-icon" />
<link rel="shortcut icon" href="/assets/img/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="/assets/css/main.css" type="text/css" media="all" />
<link rel="stylesheet" href="/assets/css/signup.css" type="text/css" media="all" />
<link rel="stylesheet" href="/assets/css/print.css" type="text/css" media="print" />
</head>
<body>
<div id="wrapper">
	<header>
		<div id="logoarea">
			<a href="/"><img src="/assets/img/logo.jpg" alt="FIRNEN Logo" /></a>
		</div>
		<div id="search-form">
			<form method="post" action="/search/">
				<input type="text" name="search" value="'.$GLOBALS['searchstr'].'" required="required" maxlength="150" />
				<button type="submit" name="submit">Search</button>
			</form>
		</div>
		<nav id="menu">
			<ul id="nav-menu">'.$GLOBALS['navlinks'].'</ul>
		</nav>
	</header>

	<section class="contentblock">
		'.$GLOBALS['contentblock'].'
	</section>

	<footer>
		<p>
			All rights reserved &copy; '.date('Y').' &middot;
			<a href="http://softuni.bg" target="_blank">SoftUni</a> team FIRNEN &middot;
			<a href="http://validator.w3.org/check?verbose=1&amp;uri=http%3A%2F%2Ffirnen.info%2F" target="_blank">HTML5</a> &amp;
			<a href="http://jigsaw.w3.org/css-validator/validator?profile=css3&amp;warning=0&amp;uri=http%3A%2F%2Ffirnen.info%2F" target="_blank">CSS3</a> validated
		</p>
	</footer>
</div>
</body>
</html>
';
