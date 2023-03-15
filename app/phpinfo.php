<?php
$page=new \Thpglobal\Classes\Page;
$page->start("PHP Info");
phpinfo();
$page->end();