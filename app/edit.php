<?php // Edit one record in Table $table
// 2020-06 allow the ability to pass one hidden identifier name to be preset by cookie value
// 2023-12 big problem with hiding fields
// -- $Form->hidden is an 1d array of field names passed in cookie hidden
// -- so "field1,field2" becomes array ["id","field1","field2"];
$table=$_COOKIE["table"] ?? '';
$id=$_COOKIE["id"] ?? '';
if($id=='') $id=0;
$prefix=($id ? "Edit Record $id" : "Create new record");
$page=new \Thpglobal\Classes\Page;
$page->start("$prefix in $table");
if($table=='') Die("No table set.");
$form=new \Thpglobal\Classes\Form;
$hidden_fields="id".$_COOKIE["hidden"]; // string like "field1,field2";
$form->debug("Hidden fields",$hidden_fields);
$form->start($db,"/update");
// the values are in cookies by those names
if($hidden_fields) $form->hidden=explode($hidden_fields,",");
$form->record($table,$id);
$form->end("Save data");
$page->end();
