<?php
// test format of 4 charts
$page=new Thpglobal\Classes\Page;
$page->start("Test Charts");
$chart=new Thpglobal\Classes\Chart;
$chart->start();
/*
$chart->show("Test","pie");
$chart->show("Test","bar");
$chart->show("Test","radar");
$chart->end();
echo("<section>\n");
*/
$x=["A","B","C"];
$y=[1,2,3];
$chart->make(1,"Test 1","pie",$x,$y);
$chart->make(2,"Test 2","bar",$x,$y);
$chart->make(3,"Test 3","radar",$x,$y);
$chart->make(4,"Test 4","line",$x,$y);
$chart->end();

$page->end();


