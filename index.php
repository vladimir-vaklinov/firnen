<?php
/**
 * <h1>Main index file</h1>
 * This is the main index file, it controls the
 * main functionality of the website.
 *
 * @version 0.1
 * @since 2014-08-17
 */
header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');// Special header for easy access to coockies
header('Content-Type:text/html; charset=utf-8');


/**
 * Predefinning some variables.
 * All GET parameters are routed to "var" variable
 * with .htaccess using mod_rewrite.
 * With the "for" cycle we are parsing this "var"
 * variable and all components separated by "/" are
 * send back to $_GET[] variable. URL like this:
 * http://website.com/task1/task2/
 * is routed to: http://website.com/index.php?var=/task1/task2
 * and then we create GET variable like: $_GET['task_0'] = 'task1',$_GET['task_1']='task2'
 */
$get_variables=array();$var=array();
if(isset($_GET['var']))$var=explode("/",$_GET['var']);

for($g=0;$g<count($var);$g++){
	$get_var_arr=explode("_",$var[$g]);
	if(@count($get_var_arr)>1){
		if(isset($get_var_arr[1]) && ($get_var_arr[1] != null))$_GET[$get_var_arr[0]]=$get_var_arr[1];
	}else{
		$_GET['task_'.$g]=$get_var_arr[0];
	}
}

$GLOBALS['pageTitle']="CONTENT";
$GLOBALS['contentblock']="CONTENT";
$GLOBALS['searchstr']="";
$GLOBALS['navlinks']="";

include(__DIR__."/../../_conf/global.conf.php");
include(__DIR__."/lib/database.inc.php");
include(__DIR__."/lib/websiteCore.inc.php");


if(!isset($_SESSION['user']) || (isset($_GET['task_0']) && ($_GET['task_0'] == 'logout')) ){
	$_SESSION['user']=array();
}

$db=new SQL($GLOBALS['dbconf']);
$ws=new websiteCore();


$GLOBALS['navlinks']=$ws->mainNavigation();



if(isset($_GET['task_0']) && ($_GET['task_0']=='signup')){

	include(__DIR__."/lib/signUp.inc.php");
	$su=new signUp();
	$GLOBALS['contentblock']=$su->actions();
	$GLOBALS['contentblock']=$su->htmlForms();

}



include(__DIR__."/htmls.inc.php");
print $GLOBALS['html'];