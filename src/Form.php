<?php
// CLASS FORM - EDIT A RECORD
// 2023-03 simplify, remove css classes and divs, embed inputs in label instead of label for

namespace Thpglobal\Classes;

class Form {
	protected $db;
	public $data=array();
	public $minNumAll;
	public $maxNumAll;
	public $hidden=["id"]; 
	public $ignore=array();
	public $where=array(); // ability to add filters to dropdowns inside ->record

	public function debug($name,$item){
		if(boolval($_COOKIE["debug"] ?? FALSE)) {
			echo("<p>$name: "); print_r($item); echo("</p>\n");
		}
	}
	
	public function start($db=NULL,$action="/update"){
		$this->db=$db; // reference database connection
		echo("<form method='post'");
		if($action>'') echo (" action='$action'");
		echo(">\n<Fieldset>\n");
	}
	public function end($submit="Save Data"){
		echo("\n<button type=submit>$submit</button>\n</fieldset>\n</form>\n");
	}
	public function data($array) { // these are the existing values
		$this->data=$array;
	}
	public function toggle($name) {
		echo("<input name=$name type=hidden value=0>\n");
		echo('<label role=toggle>'.$name.'<input name="'.$name.'" role=toggle type="checkbox"');
		if($this->data[$name]??0>0) echo(" checked");
		echo('><div role=toggle></div></label>'."\n");
	}
	public function rename($name,$showname) {
		$value=$this->data[$name]??'';
		if($value=='') $value=0;
		$label=ucwords($showname);
		if($min<>NULL) $label .= "$min to $max";
		echo("<br><label>$name ".ucwords($name).":");
		echo("<input type=number name='$name' value='$value'");
		if($min<>NULL) echo(" min='$min'");
		if($max<>NULL) echo(" max='$max'");
		if($min<>NULL) echo("><span class=status></span");
		echo("></label>\n");
	}
	
	public function num($name,$min=NULL,$max=NULL){
		$value=$this->data[$name]??$min;
		if(isset($this->minNumAll)) $min=$this->minNumAll;
		if(isset($this->maxNumAll)) $max=$this->maxNumAll;
		if($value=='') $value=0;
		$label=ucwords($name);
		if($min<>NULL) $label .= "$min to $max";
		echo("<br><label>$label:");
		echo("<input type=number name='$name' value='$value'");
		if($min<>NULL) echo(" min='$min'");
		if($max<>NULL) echo(" max='$max'");
		if($min<>NULL) echo("><span class=status></span");
		echo("></label>\n");
	}
	public function text($name,$rename='',$minlength=0){
		$label=($rename>'' ? $rename : $name);
		$value=$this->data[$name]??'';
		echo("<br><label>".ucwords($label).":");
		echo("<input type=text name='$name' value='$value'");
		if($minlength>0) echo(' required><span class=status></span');
		echo("></label>\n");
	}
	public function date($name,$required=0){ // This restricts daterange to mindate/maxdate if set
		$preset=$this->data[$name]??"";
		echo("<br><label>".ucwords($name).":");
		echo("<input type=date name='$name' value='$preset'");
		if(isset($_COOKIE["mindate"])) echo(" min='".$_COOKIE["mindate"]."'");
		if(isset($_COOKIE["maxdate"])) echo(" max='".$_COOKIE["maxdate"]."'");
		if($required) echo (' required');
		echo("><span class=status></span></label>\n");
	}
	public function textarea($name,$rename='',$required=0){
		$val=$this->data[$name]??'';
		$label=($rename>'' ? $rename : $name);
		echo("<br><label>".ucwords($label).":<br>\n");
		echo("<textarea name=$name rows=5 cols=60 spellcheck=false");
		if($required) echo(" REQUIRED");
		echo(">$val</textarea>\n");
		if($required) echo("<span class=status></span>");
		echo("</label>\n");
	}
	public function hide($name,$value){
		echo("<input type=hidden name='$name' value='$value'>\n");
	}
	public function pairs($name,$array,$required=0){
        $requiredAttr=($required) ? ' required ' : '';
        //HtML5 requires required value to be empty (not zero) for validation
        $requiredVal=($required) ? '' : 0;
		$val=$this->data[$name]??0;
        echo("<br><label>".ucwords($name).":");
        echo("<select name='$name' $requiredAttr>\n<option value='$requiredVal'>(Select)\n");
        foreach($array as $key=>$value){
            echo("<option value='$key'");
            if($key==$val) echo(" selected");
            echo(">$value\n");
        }
        echo("</select>");
        if($required){echo "<span class=status></span>";}
        echo("</label>\n");
    }
	public function query($name,$query,$required=0){
		$pdo_stmt=$this->db->query($query);
		if(!is_object($pdo_stmt)) Die("Fatal Error - bad query - $query \n");
		$this->pairs($name,$pdo_stmt->fetchAll(12),$required);
	}
	public function record($table,$id){ // goes inside start and end
		// Also can create a new record if id==0
		// First pull in the list of field meta data
		if($id=='') $id=0;
		$this->hide("table",$table);
		$pdo_stmt=$this->db->query("select * from $table where id='$id'");
		if(!is_object($pdo_stmt)) Die("Fatal Error - bad query - $query \n");
		$this->data = $pdo_stmt->fetch(2);
		
		foreach(range(0, $pdo_stmt->columnCount() - 1) as $column_index)
		{ $meta[$column_index] = $pdo_stmt->getColumnMeta($column_index);}
		
		$this->debug("Meta",$meta);
		$this->debug("Hidden",$this->hidden);
		foreach(range(0, $pdo_stmt->columnCount() - 1) as $column_index) {
			$name=$meta[$column_index]["name"];
			$type=$meta[$column_index]["native_type"];
			$value=$this->data[$name]??'';
			if($name=="id"){
				$this->hide($name,$id);
			}elseif(isset($this->hidden[$name])){
				$this->hide($name,$this->hidden[$name]);
			}elseif($name=='User_Email'){
				$this->hide($name,strtolower($_SERVER["USER_EMAIL"]));
			}elseif(substr($name,-3)=="_ID"){
				$subtable=strtolower(substr($name,0,-3));
				$where=$this->where[$name]??'';
				echo("\n<!-- where $where -->\n");
				$this->query($name,"select id,name from $subtable $where order by 2");
			}elseif($type=="TINY") {
				$this->toggle($name);
			}elseif($type=="LONG") {
				$this->num($name);
			}elseif($type=="BLOB") {
				$this->textarea($name);
			}elseif($type=='DATE'){
				$this->date($name);
			}elseif(!in_array($name,$this->ignore)) {
				$this->text($name);
			}
		}
	}
} // END OF CLASS FORM
