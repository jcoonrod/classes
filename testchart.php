<?php
// test format of 4 charts
$page=new Thpglobal\Classes\Page;
$page->start("Test Charts");
$chart=new Thpglobal\Classes\Chart;
$chart->start();
$x=[1,2,3,4,5];
$y=[1,4,9,16,25];
$chart->colors=["green","red","blue","orange","violet"];
$chart->make(1,"Test","pie",$x,$y);
$chart->make(2,"Test","bar",$x,$y);
$chart->make(3,"Test","radar",$x,$y);
$chart->end();
$page->end();


