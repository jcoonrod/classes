<?php // Edit one record in Table $table
// 2020-06 allow the ability to pass one hidden identifier name to be preset by cookie value
// 2023-12 big problem with hiding fields
// -- $Form->hidden is an 1d array of field names passed in cookie hidden
// -- so "field1,field2" becomes array ["id","field1","field2"];
$table=$_COOKIE["table"] ?? '';
$id=$_COOKIE["id"] ?? '';
$hide=$_COOKIE["hide"] ?? '';
if($id=='') $id=0;
$prefix=($id ? "Edit Record $id" : "Create new record");
$page=new \Thpglobal\Classes\Page;
$page->start("$prefix in $table");
if($table=='') Die("No table set.");
$form=new \Thpglobal\Classes\Form;
if($hide) {
    $hidden_fields=explode(",",$hide);
    foreach($hidden_fields as $field) {
       $cookie_name=(substr($field,-3)=="_ID" ? strtolower(substr($field,0,-3)) : '');
       $form->hidden[$field]=$_COOKIE[$cookie_name]??'';
    }
}
$form->debug("Hidden fields",$hidden_fields);
$form->start($db,"/update");
// the values are in cookies by those names
$form->record($table,$id);
$form->end("Save data");
$page->end();
