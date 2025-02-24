<!-- The following are used to store past copies of the query in local storage -->
<!-- Moved to top in case there is a error in the query -->
<script>
  var queries = [];
  if (!localStorage.queries)
    window.localStorage.setItem('queries',JSON.stringify(queries));
  queries=JSON.parse(window.localStorage.getItem('queries'));
  var n=queries.length;
  var i=n;
  showni();
function showtables(){
	document.getElementById('q').textContent="show tables";
}
function saveq(){
  queries[n]=document.getElementById('q').value;
  window.localStorage.setItem('queries',JSON.stringify(queries));
}
function goback(){
  i=i-1;
  if(i<0) i=0;
  document.getElementById('q').textContent=queries[i];
  showni();
}
function gofwd(){
  i=i+1;
  if(i>(n-1)) i=n-1;
  if(i<0) i=0;
  document.getElementById('q').textContent=queries[i];
  showni();
}
function goclear(){
  window.localStorage.clear();
  queries=[];
  n=0;
  i=0;
  document.getElementById('q').textContent='';
  showni();
}
function showni(){
  document.getElementById('nq').textContent=n;
  document.getElementById('iq').textContent=i;
}
</script>
<?php
// Simple query display
$page=new Thpglobal\Classes\Page;
$page->icon("download","/export","Download as excel");
$page->icon("pie-chart","chart?chart_type=pie","Display as Pie Chart");
$page->icon("bar-chart","chart?chart_type=bar","Display as Bar Chart");
$page->icon("line-chart","chart?chart_type=line","Display as Line Chart");
$page->start("Query");
?>
<p><button onclick="showtables(); ">Show tables</button> 
<button onclick="goback()">&lt;</button> 
<button onclick="gofwd()">&gt;</button> 
<button onclick="goclear()"><span class='fa fa-trash'></span></button> 
<span id=nq>0</span> queries stored. Loading <span id=iq>0</span>.</p>
<?php
$query=$_GET["query"]??'';
echo("<form><textarea id=q name=query rows=3 cols=80>$query</textarea><input type=submit onclick='saveq()'></form>\n");
if($query){
	$start=substr($query,0,4); // determine type of event based on first 4 letters
	if(in_array($start,array("show","sele","expl"))){
		$grid=new \Thpglobal\Classes\Table;
		$grid->start($db);
		$grid->query($query);
		$grid->show();
	}else{
    if(!ADMIN) Die("Not authorized");
    require_once($_SERVER["DOCUMENT_ROOT"]."/includes/root.php");
    $count=$db->exec($query);
    echo("<p>Rows affected: $count</p>");
	}
}
?>

<?php 
$page->end();
