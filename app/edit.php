<?php // Edit one record from old budget tables
// 2020-06 allow the ability to pass one hidden identifier name to be preset by cookie value
$table=$_COOKIE["table"] ?? '';
$id=$_COOKIE["id"] ?? '';
$hide=$_COOKIE["hidden"] ?? ''; // Allow one hidden variable to be passed in the url
if($hide) {
	// is it the name of a dropdown link?
	$n=strpos($hide,"_ID"); 
	$cookie_name= strtolower( ($n ? substr($hide,0,$n) : $hide));
	$hidden=array($hide=>$_COOKIE[$cookie_name]);
}													
$hide2=$_COOKIE["hidden2"] ?? ''; // Allow a second hidden variable to be passed in the url
if($hide2) {
	// is it the name of a dropdown link?
	$n=strpos($hide2,"_ID"); 
	$cookie_name= strtolower( ($n ? substr($hide2,0,$n) : $hide2));
	$hidden2=array($hide2=>$_COOKIE[$cookie_name]);
}													

if($_COOKIE["debug"]) print_r("Hidden",$hidden);
if($id=='') $id=0;
$prefix=($id>0 ? "Edit Record $id" : "Create new record");
$page=new \Thpglobal\Classes\Page;
$page->start("$prefix in $table");
if($table=='') Die("No table set.");
$form=new \Thpglobal\Classes\Form;
$form->start($db,"/update");
if($hide) $form->hidden=$hidden;
if($hide2) $form->hidden=$hidden2;
$form->record($table,$id);
$form->end("Save data");
$page->end();
