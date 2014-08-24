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

$get_variables=array();
$var=array();
if(isset($_GET['var']))
	$var=explode("/", $_GET['var']);

for($g=0; $g<count($var); $g++){
	$get_var_arr=explode("_", $var[$g]);
	if(@count($get_var_arr)>1){
		if(isset($get_var_arr[1])&&($get_var_arr[1]!=null))
			$_GET[$get_var_arr[0]]=$get_var_arr[1];
	}else{
		$_GET['task_'.$g]=$get_var_arr[0];
	}
}

include(__DIR__."/../../../_conf/global.conf.php");
include(__DIR__."/../lib/database.inc.php");
include(__DIR__."/lib/init.inc.php");

$init=new initAdmin();


$GLOBALS['contentblock']="";
$contents="";


/**
 * <h1>Check for Administrator Login</h1>
 *
 */

if(isset($_GET['task_0'])&&($_GET['task_0']=='logout')){
	$_SESSION['firnen_admin_login']=0;
	@header("Location:/admin/");
}

include(__DIR__."/lib/login.inc.php");
$login=new login();

if(isset($_SESSION['firnen_admin_login'])&&($_SESSION['firnen_admin_login']!=1)){

	$GLOBALS['contentblock']=$login->loginForm();

	include(__DIR__."/htmls.inc.php");
	exit($GLOBALS['html']);
}

include(__DIR__."/lib/adminCore.inc.php");
$ac = new adminCore();

$db = new SQL($GLOBALS['dbconf']);

if(isset($_GET['task_0'])&&($_GET['task_0']=='users')){

	include(__DIR__."/lib/usersAdmin.inc.php");
	$ua = new usersAdmin();
	$contents=$ua->contents();

}
if(isset($_GET['task_0'])&&($_GET['task_0']=='settings')){

	include(__DIR__."/lib/settingsAdmin.inc.php");
	$sa = new settingsAdmin();
	$contents=$sa->contents();

}






$GLOBALS['contentblock']=$ac->contents($contents);

include(__DIR__."/htmls.inc.php");
print $GLOBALS['html'];