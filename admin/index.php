<?php
/**
 * <h1>Main admin index file</h1>
 * This is the admin index file, it's controls the
 * main functionality of the website.
 *
 * @author Vasil Tsintsev
 * @version 0.1
 * @since 2014-08-17
 *
 */
header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"'); // Special header for easy access to coockies
header('Content-Type:text/html; charset=utf-8');


include(__DIR__."/../../../_conf/global.conf.php");
include(__DIR__."/../lib/database.inc.php");
include(__DIR__."/lib/init.inc.php");

$init=new initAdmin();


$GLOBALS['contentblock']="Login Please";


/**
 * <h1>Check for Administrator Login</h1>
 *
 */

if(isset($_POST['action'])&&($_POST['action']=='logout'))
	$_SESSION['firnen_admin_login']=0;

if(isset($_SESSION['firnen_admin_login'])&&($_SESSION['firnen_admin_login']!=1)){
	include(__DIR__."/lib/login.inc.php");

	$login=new login();
	$GLOBALS['contentblock']=$login->loginForm();

	include(__DIR__."/htmls.inc.php");
	exit($GLOBALS['html']);
}


include(__DIR__."/htmls.inc.php");
print $GLOBALS['html'];