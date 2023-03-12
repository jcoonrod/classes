<?php
namespace jcoonrod\Classes;
// START CLASS PAGE
class Page {
	public $datatable = FALSE;
	public $addStickyHeader = TRUE;
//	public $css=array("https://storage.googleapis.com/thp/thp.css"); // default used by all
	public $preh1=""; // used for dashboard colorbar etc
	public $time_start; // used to measure length for process
	public $links=array("print"=>"'javascript:window.print();'");
	public $hints=array("print"=>"Print this page");
	public $appendTitle='';
	public function debug($message,$values) {
		echo("<p>$message".":"); print_r($values); echo("</p>\n");
	}

	public function datatable(){
		$this->datatable=TRUE;
	}
	public function disableStickyHeader(){
		$this->addStickyHeader=FALSE;
	}

/* dynamic property setter/getter for this class */
	public function get($prop){
		if(isset($this->$prop)){
			return $this->$prop;
		}
		return NULL;
	}
	public function set($prop, $value){
		if(isset($this->$prop)){
			$this->$prop = $value;
		}
	}
	public function menu() { // new classless responsive version
		$menu=$_SESSION["menu"];
		if(isset($_SESSION["menu"]) and sizeof($menu)>0) {
			?>
		<nav>
		  <ul>
    		<li><a href=/><strong>Home</strong></a></li>
			<?php
			foreach($menu as $key=>$item) {
				if(is_array($item) ){
					echo("\t<li>\n\t\t<details role='list' dir='rtl'>\n\t\t\t<ul role='listbox'>\n");
					foreach($item as $b=>$a) echo("\t\t\t\t<li><a href='$a'>$b</a></li>\n");
					echo("\t\t\t</ul>\n\t\t</details>\n\t</li>\n");
				}else{
				echo("\t<li><a href='$item'>$key</a></li>\n");
				}
			}
			?>
		</ul>
	</nav>
	<?php
	}}
	
	public function start_light($title="THP",$lang="en") { // no menu, no icons, no datatable, no extras
		foreach($_GET as $key=>$value) $_SESSION[$key]=$value;
		$this->time_start=microtime(true);
		echo("<!DOCTYPE html>\n<html lang=$lang>\n<head>\n");
		echo("<meta name=viewport content='width=device-width, initial-scale=1'>\n");
		echo("<title>$title</title>\n");
		echo("<meta name='description' content='$title built on opensource github.com/jcoonrod/classes'/>\n");
		echo("<link rel='shortcut icon' href='/static/favicon.png'>\n");
		echo("<link rel='stylesheet' href='pico.classless.css'>\n");
		echo("<meta charset='utf-8'>\n");
        echo("</head>\n<body>\n");
        echo("<header>\n");
		echo("<h1>$title</h1>\n");
	}

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
	<link rel='stylesheet' href='/static/pico.classless.css'>
	<link rel='stylesheet' href='/static/font-awesome.css'>
	<meta charset='utf-8'>
</head>
<body>
<?php 
		$this->menu();
		echo("\t<h1>$title");
		foreach($this->links as $key=>$link) {
            $hint=$this->hints[$key];
            echo("<a href=$link class='fa fa-$key' title='$hint'></a>\n");
        }
        echo($this->appendTitle."</span></h1>\n");
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
		echo("<p><i>$message Run time: $time</i></p>\n");
		echo("</div>\n");
        echo("</body></html>\n");
    }
}
// END CLASS PAGE
