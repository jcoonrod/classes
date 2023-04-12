<?php
$page=new Thpglobal\Classes\Page;
$page->start("Test Charts");
$chart=new Thpglobal\Classes\Chart;
$chart->start();
$chart->show("Test 1","pie");
$chart->show("Test 2","bar");
$chart->show("Test 3","radar");
echo($chart->line());
$chart->end();

$page->end();
