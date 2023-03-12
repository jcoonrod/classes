<?php 
// Local test program 

require_once(__DIR__."/src/Page.php");
require_once("src/Filter.php");
require_once("src/Form.php");
require_once("src/Table.php");

session_start();
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL & ~E_NOTICE);

function getcookie($x) {
	if( array_key_exists($x,$_COOKIE)) return $_COOKIE[$x];
	return "";
}

$page=new \jcoonrod\classes\Page;

$_SESSION["menu"]=["Test 1"=>"/test1","Test 2"=>"/test2","Test 3"=>["Sub 1"=>"/sub1","Sub 2"=>"/sub2","Sub 3"=>"/sub3"]];

$page->start("Hello World");
$page->end("That's all folks!");
