<?php
$input = array_map("trim",file(__DIR__."/input.txt"));

//split our ranges into an array of two values, the min and max
$ranges = [];
foreach($input as $i){
	list($min,$max) = explode("-",$i);
	$ranges[] = [$min,$max];
}

//sort the ranges
uasort($ranges,function($a,$b){
	$x = bccomp($a[0],$b[0]);
	if($x != 0){
		return $x;
	}
	return bccomp($a[1],$b[1]);
});

//we need to collapse this down to where we don't have any overlapping ranges
while(count($ranges) > 1){
	$c = array_shift($ranges);
	while(1){
		$n = array_shift($ranges);
		if(inRange($n[0],$c[0],$c[1]) && inRange($n[1],$c[0],$c[1])){
			//ignore it, it's consumed by our current range
		} elseif(inRange($c[1],$n[0],$n[1])){
			//the high part of the current range is within the next range,
			//so change our high range to the that of the next range
			$c[1] = $n[1];
		} elseif(bccomp(bcadd($c[1],1),$n[0]) == 0){
			//in this case, the next range STARTS one higher than our current range ends
			//meaning they can be combined... aka, set our current ranges max to the next ranges max
			//we could get the correct answer for part 2 without this step, since we'd still
			//have distinct ranges, but this step allows us to find the answer to part 1 as well.
			$c[1] = $n[1];
		} else {
			$r[] = $c;
			array_unshift($ranges,$n);
			break;
		}
	}
}
echo "Condensed ".count($input)." to ".count($r)." ranges.".PHP_EOL;


$totalIps = "4294967296"; //one more than the max IP, since 0 is allowed

foreach($r as $s){
	$rangeDiff = bcsub($s[1],$s[0]);
	$rangeNum = bcadd($rangeDiff,1); //9-5 = 4, but the range from 5-9 contains 5 numbers, and we need to subtract out the number of items in the range
	$totalIps = bcsub($totalIps,$rangeNum);
}

echo "There are {$totalIps} allowed.".PHP_EOL;
echo "Lowest allowed IP is ".($r[0][1]+1).PHP_EOL;

function inRange($x,$a,$b){
	return (bccomp($x,$a) >= 0 && bccomp($x,$b) <= 0);
}
