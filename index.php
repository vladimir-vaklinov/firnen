<?php
/**
 * <h1>Main index file</h1>
 * This is the main index file, it's controls the
 * main functionality of the website.
 *
 * @author Vasil Tsintsev
 * @version 0.1
 * @since 2014-08-17
 *
 */
header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');// Special header for easy access to coockies
header('Content-Type:text/html; charset=utf-8');

$get_variables=array();
$var=array();
if(isset($_GET['var']))$var=explode("/",$_GET['var']);

for($g=0;$g<count($var);$g++){
	$get_var_arr=explode("_",$var[$g]);
	if(@count($get_var_arr)>1){
		if(isset($get_var_arr[1]) && ($get_var_arr[1] != null))$_GET[$get_var_arr[0]]=$get_var_arr[1];
	}else{
		$_GET['task_'.$g]=$get_var_arr[0];
	}
}

include(__DIR__."/../../_conf/global.conf.php");
include(__DIR__."/lib/database.inc.php");


$db=new SQL($GLOBALS['dbconf']);

$GLOBALS['pageTitle']="CONTENT";
$GLOBALS['contentblock']="CONTENT";



include(__DIR__."/htmls.inc.php");
print $GLOBALS['html'];