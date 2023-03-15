<?php
$page=new \Thpglobal\Classes\Page;
$page->start("Test form");
$form=new \Thpglobal\Classes\Form;
$form->start("","/cookies");
$form->text("name");
//$form->range("n",1,11);
$form->toggle("SDG");
$form->date("this_date");
$form->end();
$page->end("Form done!");