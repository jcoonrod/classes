<?php

$page=new Thpglobal\Classes\Page;
$page->icon("download","/export","Download Excel file");

$page->start("Hello World");
echo("<h2 class=jumbotron>Here is a jumbotron</h2>\n");

$grid=new Thpglobal\Classes\Table;

$grid->contents=[["ID","Name","Age"],[1,"John",72],[2,"Carol",78]];

$grid->show();

$page->end("That's all folks!");
