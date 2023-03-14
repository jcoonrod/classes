<?php
$page=new Thpglobal\Classes\Page;
$page->icon("download","/export","Download Excel file");

$page->start("Hello World");

$grid=new Thpglobal\Classes\Table;

$explain=$db->query("Explain cars");
$row = $results->fetchArray();
$grid->row($row);

$results = $db->query('SELECT * FROM cars');
while ($row = $results->fetchArray()) {
    echo("<p>".print_r($row,TRUE)."</p>\n");
    $grid->row($row);
}

$grid->show();

$page->end("That's all folks!");
