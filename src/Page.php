<?php
namespace Thpglobal\Classes;
// START CLASS PAGE
class Page {
	public $time_start; // used to measure length for process
	public $links=array("print"=>"'javascript:window.print();'");
	public $hints=array("print"=>"Print this page");
	public $appendTitle='';
	public function debug($message,$values) {
		echo("<p>$message".":"); print_r($values); echo("</p>\n");
	}
	public function menu() { // new classless responsive version
		$menu=$_SESSION["menu"];
		if(isset($_SESSION["menu"]) and sizeof($menu)>0) {
			?>
		<nav>
		  <ul>
			<?php
			foreach($menu as $key=>$item) {
				if(is_array($item) ){
					$top=$key."▼";
					echo("\t<li>$top\n");
					echo("\t\t\t<ul>\n");
					foreach($item as $b=>$a) echo("\t\t\t\t<li><a href='$a'>$b</a></li>\n");
					echo("\t\t\t</ul>\n\t</li>\n");
				}else{
				echo("\t<li><a href='$item'>$key</a></li>\n");
				}
			}
			?>
		</ul>
	</nav>
	<?php
	}}
	
	public function start($title="THP",$lang="en"){
		$reply=$_COOKIE["reply"] ?? '';
		setcookie("reply","",0,'/');
		$_SESSION["datatable"]=$this->datatable; // save for access by Table class
		foreach($_GET as $key=>$value) $_SESSION[$key]=$value;
		$this->time_start=microtime(true);
		?>
<!DOCTYPE html>
<html lang=<?php echo $lang?>>
<head>
	<meta name=viewport content='width=device-width, initial-scale=1'>
	<title><?php echo $title?></title>
	<meta name='description' content='$title built on opensource github.com/jcoonrod/classes'/>
	<link rel='shortcut icon' href='/static/favicon.png'>
	<link rel='stylesheet' href='/static/font-awesome.css'>
	<link rel='stylesheet' href='/static/classes.css'>
<?php 
// add optional local style.css
		$f=$_SYSTEM["DOCUMENT_ROOT"]."/static/style.css";
		if (file_exists($f)) echo("<link rel='stylesheet' href='/static/style.css'>\n");
?>
	<meta charset='utf-8'>
</head>
<body>
<?php 
		$this->menu();
		echo("\t<main><h1>$title");
		foreach($this->links as $key=>$link) {
            $hint=$this->hints[$key];
            echo(" <a href=$link class='fa fa-$key' title='$hint'></a>\n");
        }
        echo($this->appendTitle."</h1>\n");
		if($reply){
			$color="green";
			if(substr($reply,0,5)=="Error") $color="red";
			echo("<p style='text-align:center;color:white;background-color:".$color."'>$reply</p>\n");
		}
/* -- skip this for now		
		echo("<div id='google_translate_element' style='position:absolute; top:4em; right:1em;'></div>
			<script type='text/javascript'>
				function googleTranslateElementInit() {
					new google.translate.TranslateElement({pageLanguage: '$lang'}, 'google_translate_element');
				}
			</script>
		<script type='text/javascript' src='//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit'></script>");
*/		
	}
	public function icon($type="edit",$link="/edit",$hint="Edit this record"){
		$this->links[$type]=$link;
		$this->hints[$type]=$hint;
	}
	public function toggle($name,$on_msg='On',$off_msg='Off'){
		$now=$_COOKIE[$name];
		if($now<>'off') $now='on'; // default is ON
		$then=($now=='on' ? 'off' : 'on');
		$this->appendTitle.="<a class='fa fa-toggle-$now' href='?$name=$then'></a> ";
		$this->appendTitle .= ($now=='on' ? $on_msg : $off_msg) ;
	}
	
	public function end($message=""){
		$time=microtime(true)-($this->time_start);
		echo("</main><footer><p><i>$message Run time: $time</i></p>\n");
		echo("</footer>\n");
        echo("</body></html>\n");
    }
}
// END CLASS PAGE
