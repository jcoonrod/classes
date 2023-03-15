<?php
$page=new \Thpglobal\Classes\Page;
$page->start("Test filter");
$filter=new \Thpglobal\Classes\Filter;
$filter->start();
$day1=$filter->date("day1");
$year1=$filter->range("year1",2008, 2030);
$fruit=$filter->pairs("fruit",["Apples","Bananas","Crrots"]);
$filter->end();
$page->end("Filter done!");