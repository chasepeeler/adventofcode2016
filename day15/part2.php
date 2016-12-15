<?php

if($argv[1] == "TEST"){
	$input_file = "sample_input.txt";
} else {
	$input_file = "input.txt";
}
$input = array_map("trim",file(__DIR__."/".$input_file));

$discs = [];

foreach($input as $i){
	preg_match('/Disc #(\d+) has (\d+) positions; at time=(\d+), it is at position (\d+)/',$i,$m);
	$disc = createDisc($m[2],$m[4]);
	
	//my input has everything at time =0, so skipping what would be necessary to adjust things back to time=0
	//if another time was given

	$discs["Disc".$m[1]] = $disc;
}
//add new disc for part 2
$discs["Disc".(count($discs)+1)] = createDisc(11,0);

$time = 0;

while(true){

	//make a copy to do a "simulation" with
	$currentDiscs = cloneDiscs($discs);
	
	//it hits the first disc at time=1, so we'll start there
	$start = $time+1;
	$end = $start + count($currentDiscs);
	for($d=1,$tt=$start;$tt<$end;$tt++,$d++){
		//move our simulation discs
		moveDiscs($currentDiscs);
		if(intval($currentDiscs["Disc{$d}"]->current()) != 0){
			//it couldn't get through the current disc, so, this simulation failed
			
			//increase the time we push the button by 1
			$time++;
			
			//move our discs by one since we'll be starting a second later
			moveDiscs($discs);
			
			//restart simulation with new time and disc positions
			continue 2;
		}
	}
	
	echo "Start at time={$time}".PHP_EOL;
	break;
}

function createDisc($positions,$current){
	$a = new ArrayIterator(range(0,$positions-1));
	$disc = new InfiniteIterator($a);
	while($disc->current() != $current){
		$disc->next(); //get disc into proper position
	}
	return $disc;
}

function printDiscs($discs){
	foreach($discs as $key=>$disc){
		echo "{$key} is ".intval($disc->current()).PHP_EOL;
	}
}

function cloneDiscs($discs){
	$new = [];
	foreach($discs as $key=>$disc){
		$new[$key] = createDisc(count($disc->getInnerIterator()),$disc->current());
	}
	return $new;
}

function moveDiscs($discs){
	foreach($discs as $disc){
		$disc->next();
	}
}












