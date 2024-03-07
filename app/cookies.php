<?php

$page=new \Thpglobal\Classes\Page;
$page->start("Cookies");
echo("<h2>Constants</h1>\n");
echo("<pre>".print_r(get_defined_constants(TRUE),TRUE)."</pre>\n");
echo("<pre>".print_r($_COOKIE,TRUE)."</pre>");
echo("<h2>Post</h2>\n<pre>".print_r($_POST,TRUE)."</pre>\n");
$page->end();
