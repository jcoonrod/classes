<?php
$page=new \Thpglobal\Classes\Page;
$page->start("Test DataTable");
$a=["ABC","DEF","ghi"];
$b=["ZYX","UVW","mno"];
$grid=new Thpglobal\Classes\Table;
$grid->contents[0]=["First","Second","Third"];
for($i=1;$i<80;$i+=4){
    $grid->contents[$i]=$a;
    $grid->contents[$i+1]=$b;
    $grid->contents[$i+2]=$a;
    $grid->contents[$i+3]=$b;
}
$grid->show_datatable();
$page->end("Done!");