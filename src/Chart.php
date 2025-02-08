<?php
namespace Thpglobal\Classes;

// Class imitages Chart but by generating SVGs instead of javascript

// A small PHP program and function that draws a pie chart in SVG format. 
// originally written by Branko Collin in 2008.
class Chart{

    public $colors = array('#4d4d4d','#5da5da','#faa43a','#60bd68','#f17cb0','#b2912f',
    '#b276b2','#decf3f','#f15854','aqua','brown','salmon','olive');
    public $width=400; // each svg width in pixels, height automatic

    public function start() { echo("<section>\n");}
    public function end() { echo("</section>\n");}

    public function show($title="Sample",$type="radar",$data=array("Apple"=>50,"Banana"=>75,"Cat"=>90,"Donkey"=>35,"Eagle"=>55)) {
		foreach($data as $key=>$value) { $x[]=$key; $y[]=$value; };
        if($type=='radar') $svg=$this->radar($y,$x);
        elseif($type=='bar') $svg=$this->bar($y,$x);
        elseif($type=='line') $svg=$this->line();
        else $svg=$this->piechart($y,$x);
        echo("<div><h3>$title</h3>$svg</div>\n");
	}
    public function piechart($data, $labels) {
        $cx=200; $cy=200; $radius=190;
        $svg = '<svg viewBox="0 0 400 400" width=400 height=auto>'
        .'<style>.wt { font: bold sans-serif; fill: white; }</style>';
        $max = count($data);	
        $sum = array_sum($data);
        $deg = $sum/360; // one degree
        $jung = $sum/2; // necessary to test for arc type
        $dx = $radius; // Starting point: 
        $dy = 0; // first slice starts in the East
        $oldangle = 0;
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
        	$svg .= '<path d="M'.$cx.' '.$cy; // move cursor to center
        	$svg .= " L$adx $ady "; // draw line away away from cursor
        	$svg .= " A$radius,$radius 0 $laf,1 $ax,$ay "; // draw arc
        	$svg .= " Z\" "; // z = close path
        	$svg .= " fill=\"$colour\" stroke=\"white\" stroke-width=\"2\" ";
        	$svg .= " stroke-linejoin=\"round\" />";
        	$svg .='<text class="wt" text-anchor="middle" x="'.$tx.'" y="'.$ty.'" style="fill:white">'.$text.'%</text>';
        	$svg .='<text class="wt" text-anchor="middle" x="'.$tx.'" y="'.($ty-17).'" style="fill:white">'.$labels[$i].'</text>';
        	$dx = $x; // old end points become new starting point
        	$dy = $y; // id.
        	$oldangle = $angle;
        }
        return $svg."</svg>\n"; 
    }

    public function line($xdata=[1.1,2.2,3.3],$ydata=[1.2,4.4,9.7]){
        $xmax=max($xdata);
        $xtick=10; 
        if($xmax<50) $xtick=5; 
        if($xmax<10) $xtick=1;
        $dx=($xmax<1 ? 360 : 360/$xmax); // number of pixels between ticks
        $nx=ceil($xmax/$xtick); //how many xticks?

        $ymax=max($ydata); 
        $ytick=10; 
        if($ymax<50) $ytick=5; 
        if($ymax<10) $ytick=1;
        $dy=($ymax<1 ? 360 : 360/$ymax); // number of pixels between ticks
        $ny=ceil($ymax/$ytick); //how many xticks?

        $svg='<svg viewBox="0 0 400 400" width=400 height=auto xmlns="http://www.w3.org/2000/svg">';
// horizontal lines
        for($j=0;$j<=$ny;$j++){
            $y=380-$j*$dy;
            $svg.='<line x1="20" y1="'.$y.'" x2="400" y2="'.$y.'" stroke="blue"/>';
            $svg.='<text x="0" y="'.$y.'">'.$j*$ytick.'</text>';
        }
// vertical lines
        for($i=0;$i<=$nx;$i++){
            $x=40+$i*$dx;
            $svg.='<line x1="'.$x.'" y1="20" x2="'.$x.'" y2="380" stroke="blue"/>';
            $svg.='<text x="'.$x.'" y="400">'.$i*$xtick.'</text>';
        }
// the line
        $svg.='<polyline points="';
        for($i=0;$i<count($xdata);$i++) {
            $x=40+round($xdata[$i]*$dx/$xtick);
            $y=380-round($ydata[$i]*$dy/$ytick);
            $svg.="$x,$y ";
        }
        $svg.=' style="fill:none;stroke:red;stroke-width:3" />';
        return $svg.="</svg>\n";
    }
    
    public function bar($data,$labels) { // note - size of box is 360x360 to allow for labels
        $max=max($data); 
        if($max>=5000) $tick=1000;
        if($max<5000) $tick=500;
        if($max<500) $tick=50;
        if($max<50) $tick=5; 
        if($max<10) $tick=1;
        $nx=sizeof($data);
        $ny=ceil($max/$tick);
	$xtick=floor(360./$nx);
	$half=floor($xtick/2);
	$barwidth=floor(0.8*360./$nx);
	$gap=floor(0.2*360./$nx);						
	if(!$nx or !$ny) return "<p>No data</p>\n";

        $svg='<svg viewBox="0 0 400 400" width=400 height=auto xmlns="http://www.w3.org/2000/svg">';
        for($j=0;$j<=$ny;$j++){
            $y=380-$j*$tick;
            $svg.='<line x1="20" y1="'.$y.'" x2="400" y2="'.$y.'" stroke="blue"/>';
            $svg.='<text x="0" y="'.$y.'">'.$j*$tick.'</text>';
        }
        for($x=$gap+40;$x<=400;$x+=$xtick){
            $svg.='<line x1="'.$x.'" y1="20" x2="'.$x.'" y2="380" stroke="blue"/>';
        }
        for($i=0;$i<$nx;$i++){
	  $x=40+$i*$xtick;
          $svg.='<text x="'.$x.'" y="400" style="text-anchor: middle;">'.$labels[$i].'</text>';
          $height=floor(360*$data[$i]/($ny*$tick));
          $svg.='<rect x="'.$x.'" y="'.(380-$height).'" width="'.$barwidth.'" height="'.$height.'" fill="rgba(0,255,0,0.3)" stroke="darkgreen"></rect>';
        }
        return $svg.="</svg>\n";
    }

// called only inside radar
    private function putXY($r, $i, $n) { // convert radius and index in spider to x,y pair
        $a = (2 * pi() * $i) / $n;
        $x = floor(200 + $r * sin($a));
        $y = floor(200 - $r * cos($a));
        return ' '.floor($x).','.floor($y);
    }

    public function radar($data, $labels){
        $n = sizeof($data);
        $max=max($data); 
        $tick=10; 
        if($max<50) $tick=5; 
        if($max<10) $tick=1;
        $ny=ceil($max/$tick);
        $ytick=floor(180/$ny); // compared to bars, this is half due to center point

        $s='<svg viewBox="0 0 400 400" width=400 height=auto xmlns="http://www.w3.org/2000/svg">';
        // $s.='<style>.n {font: 10px sans-serif; fill: black;}</style>';
        for ($j=1;$j<=$ny;$j++) { // first layout the grid
            $r=$j*$ytick;
          $y = 200 - $r;
          $s.='<text x="200" y="'.$y.'">'.($j*$tick).'</text>';
          $s.='<polygon points="';
          for ($i = 0; $i < $n; $i++) { $s.=$this->putXY($r, $i, $n); }
          $s.='" fill="none" stroke="blue" /></polygon>';
        }
        // Next draw the data points
        $s.='<polygon points="';
        for ($i = 0; $i < $n; $i++) 
            $s.=$this->putXY(floor($data[$i]*$ytick/$tick), $i, $n);
        $s.='" fill="rgba(0,255,0,0.3)" stroke="darkgreen"></polygon>';
        // Next put the labels in the appropriate points
        for ($i = 0; $i < $n; $i++) {
          $a = (2 * pi() * $i) / $n;
          $x = floor(200 + 180 * sin($a));
          $y = floor(200 - 180 * cos($a));
          $s.='<text text-anchor="middle" x="'.$x.'" y="'.$y.'">'.$labels[$i].'</text>';
        }
        $s.="</svg>";
        return $s;
    }
}
