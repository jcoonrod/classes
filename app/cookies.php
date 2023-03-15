<?php

$page=new \Thpglobal\Classes\Page;
$page->start("Cookies");
echo("<pre>".print_r($_COOKIE,TRUE)."</pre>");
echo("<h2>Post</h2>\n<pre>".print_r($_POST,TRUE)."</pre>\n");
$page->end();
