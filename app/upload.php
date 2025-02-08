<?php
// Generic Spreadsheet upload - forwarding to generic import
$into=$_GET["into"]??"/dump";
$page=new \Thpglobal\Classes\Page;
$page->start("Spreadsheet Upload into $into");
echo("<p>You may only upload Excel files generated from this system.</p>\n");
echo("<form action=import enctype='multipart/form-data' method='post'>"); 
echo("<input name='userfile' type='file'>\n");
echo("<button type=submit>Upload Excel Spreadsheet File</button>\n");
echo("</form>\n");
$page->end();
