<?php 
// Local test program 

require_once(__DIR__."/src/Page.php");
require_once(__DIR__."/src/Filter.php");
require_once(__DIR__."/src/Form.php");
require_once(__DIR__."/src/Table.php");
require_once(__DIR__."/src/Chart.php");

session_start();
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL & ~E_NOTICE);

function getcookie($x) {
	if( array_key_exists($x,$_COOKIE)) return $_COOKIE[$x];
	return "";
}
function zero_cookie($c) {setcookie($c,0,0,'/'); $_COOKIE[$c]=0;}

if(!array_key_exists("debug",$_COOKIE)) zero_cookie("debug");
foreach($_GET as $key=>$value) {setcookie($key,$value,0,'/'); $_COOKIE["$key"]=$value;}

$_SESSION["menu"]=["Test Home"=>"/","Chart"=>"/testchart","Form"=>"/testform",
	"Filter"=>"/testfilter",
	"Admin"=>["Query"=>"/query","Cookies"=>"/cookies","Upload"=>"/upload"]];

// simple router between local and generic apps
$url=$_SERVER['REQUEST_URI'];
$path=parse_url($url, PHP_URL_PATH);
$generics=['/cookies','/chart', '/dump', '/edit', '/export', '/import', '/insert_table', '/list', '/logout',  '/query', '/update','/upload']; // standard routines defined in the classes

function debug($message,$values) {
		echo("<p>$message".":"); print_r($values); echo("</p>\n");
}

if(in_array($path,$generics)){
	include("./app$path.php");
}elseif($path=='/') {
	include("./app/index.php");
}else{	
	$success=include("./$path.php");
	if(!$success) Header("Location:/?reply=Error+Command+$path+Not+Found");
}

