<?php
namespace Thpglobal\Classes;

// Class imitages Chart but by generating SVGs instead of javascript

// A small PHP program and function that draws a pie chart in SVG format. 
// originally written by Branko Collin in 2008.
class Chart{

    public $colors = array('#4d4d4d','#5da5da','#faa43a','#60bd68','#f17cb0','#b2912f',
    '#b276b2','#decf3f','#f15854','aqua','brown','salmon','olive');

    public function start() { echo("<section>\n");}
    public function end() { echo("</section>\n");}

    public function show($title="Sample",$type="radar",$data=array("Apple"=>50,"Banana"=>75,"Cat"=>90,"Donkey"=>35,"Eagle"=>55)) {
		foreach($data as $key=>$value) { $x[]=$key; $y[]=$value; };
        if($type=='radar') $svg=$this->radar($y,$x);
        elseif($type=='bar') $svg=$this->bar($y,$x);
        else $svg=$this->piechart($y,$x);
        echo("<div><h3>$title</h3>$svg</div>\n");
	}
    public function piechart($data, $labels, $cx=200, $cy=200, $radius=190) {
        $chartelem = '<svg viewBox="0 0 400 400" width=400 height=auto>'
        .'<style>.wt { font: bold sans-serif; fill: white; }</style>';
        $max = count($data);	
        $sum = array_sum($data);
        $deg = $sum/360; // one degree
        $jung = $sum/2; // necessary to test for arc type
        $dx = $radius; // Starting point: 
        $dy = 0; // first slice starts in the East
        $oldangle = 0;
        if($sum) {
        /* Loop through the slices */
        	for ($i = 0; $i<$max; $i++) {
        	if($deg) $angle = $oldangle + $data[$i]/$deg; // cumulative angle
        	$x = round( cos(deg2rad($angle)) * $radius); // x of arc's end point
        	$y = round(sin(deg2rad($angle)) * $radius); // y of arc's end point
        	$text = round(100*$data[$i]/$sum); // this is the percentage
        	// at same time compute position of text
        	$text_angle=$oldangle + $data[$i]/(2*$deg);
        	$tx=200+round(cos(deg2rad($text_angle))*130); // place half-way out
        	$ty=200+round(sin(deg2rad($text_angle))*130); // place half-way out
        	$colour = $this->colors[$i];
        	$laf=($data[$i] > $jung ? 1 : 0);
        	$ax = $cx + $x; // absolute $x
        	$ay = $cy + $y; // absolute $y
        	$adx = $cx + $dx; // absolute $dx
        	$ady = $cy + $dy; // absolute $dy
        	$chartelem .= "\n";
        	$chartelem .= "<path d=\"M$cx,$cy "; // move cursor to center
        	$chartelem .= " L$adx,$ady "; // draw line away away from cursor
        	$chartelem .= " A$radius,$radius 0 $laf,1 $ax,$ay "; // draw arc
        	$chartelem .= " z\" "; // z = close path
        	$chartelem .= " fill=\"$colour\" stroke=\"white\" stroke-width=\"2\" ";
        	$chartelem .= " stroke-linejoin=\"round\" />";
        	$chartelem .='<text class="wt" text-anchor="middle" x="'.$tx.'" y="'.$ty.'" style="fill:white">'.$text.'%</text>';
        	$chartelem .='<text class="wt" text-anchor="middle" x="'.$tx.'" y="'.($ty-17).'" style="fill:white">'.$labels[$i].'</text>';
        	$dx = $x; // old end points become new starting point
        	$dy = $y; // id.
        	$oldangle = $angle;
        	}	
        	return $chartelem."</svg>\n"; 
        }
    }

    private function putXY($r, $i, $n) { // convert radius and index in spider to x,y pair
        $a = (2 * pi() * $i) / $n;
        $x = floor(120 + $r * sin($a));
        $y = floor(120 - $r * cos($a));
        return ' '.floor($x).','.floor($y);
    }
    
    public function bar($data,$labels) {
        $max=max($data); 
        $tick=10; 
        if($max<50) $tick=5; 
        if($max<10) $tick=1;
        $nx=sizeof($data);
        $ny=ceil($max/$tick);
        $xtick=floor(360/$nx);
        $ytick=floor(360/$ny);
//        echo("<p>max $max tick $tick nx $nx ny $ny xt $xtick yt $ytick</p>\n");

        $svg='<svg viewBox="0 0 400 400" width=400 height=auto xmlns="http://www.w3.org/2000/svg">';
        for($j=0;$j<=$ny;$j++){
            $y=380-$j*$ytick;
            $svg.='<line x1="20" y1="'.$y.'" x2="400" y2="'.$y.'" stroke="blue"/>';
            $svg.='<text x="0" y="'.$y.'">'.$j*$tick.'</text>';
        }
        for($x=40;$x<=400;$x+=$xtick){
            $svg.='<line x1="'.$x.'" y1="20" x2="'.$x.'" y2="380" stroke="blue"/>';
        }
        $half=floor($xtick/2);
        $quarter=floor($half/2);
        $x0=40+$half; $x1=40+$quarter;
        for($i=0;$i<$nx;$i++){
            $x=$x0+$i*$xtick;
            $svg.='<text x="'.$x.'" y="400" style="text-anchor: middle;">'.$labels[$i].'</text>';
            $x=$x1+$i*$xtick; $height=floor(360*$data[$i]/($ny*$tick));
            $svg.='<rect x="'.$x.'" y="'.(380-$height).'" width="'.$half.'" height="'.$height.'" fill="rgba(0,255,0,0.3)" stroke="darkgreen"></rect>';
        }
        $svg.="</svg>";
        return $svg;
    }

    public function radar($data, $labels){
        $n = sizeof($data);
        $s='<svg viewBox="0 0 240 240" width=400 height=auto xmlns="http://www.w3.org/2000/svg">';
        $s.='<style>.n {font: 10px sans-serif; fill: black;}</style>';
        for ($r = 10; $r < 110; $r+=10) { // first layout the grid
          $y = 120 - $r;
          $s.='<text class="n" x="121" y="'.$y.'">'.$r.'</text>';
          $s.='<polygon points="';
          for ($i = 0; $i < $n; $i++) { $s.=$this->putXY($r, $i, $n); }
          $s.='" fill="none" stroke="blue" /></polygon>';
        }
        // Next draw the data points
        $s.='<polygon points="';
        for ($i = 0; $i < $n; $i++) $s.=$this->putXY($data[$i], $i, $n);
        $s.='" fill="rgba(0,255,0,0.3)" stroke="darkgreen"></polygon>';
        // Next put the labels in the appropriate points
        for ($i = 0; $i < $n; $i++) {
          $a = (2 * pi() * $i) / $n;
          $x = floor(115 + 105 * sin($a));
          $y = floor(125 - 105 * cos($a));
          $s.='<text class="n" x="'.$x.'" y="'.$y.'">'.$labels[$i].'</text>';
        }
        $s.="</svg>";
        return $s;
    }
    public function pie2($assoc,$colors) {
        global $translations;
        // convert the associative array into an iterative data and then labels
        foreach($assoc as $key=>$value) {$data[]=$value; $labels[]=$key;}
        echo(piechart($data,$colors));
        echo("<p>");
        foreach($labels as $i=>$label) {
        	$x=(getcookie("language") ? $label : $translations[$label]);
        	echo(box($colors[$i])."&nbsp;$x, ");
        }
        echo("</p>\n");
    }
}
