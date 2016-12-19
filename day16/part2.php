<?php

$input = "01111001100111011";
$length = 35651584;
$current = $input;



do {
	$current = $current."0".getB($current);
	$step++;
} while(strlen($current) < $length);

$data = substr($current,0,$length);

$checksum = getChecksum($data);

echo $checksum;


function getB($a){
	$b = $a;
	$b = strrev($a);
	$b = str_replace("0",".",$b);
	$b = str_replace("1","0",$b);
	$b = str_replace(".","1",$b);
	return $b;
}

//method from part1 was running out of memory for the new disk size
//so changes were made to optimize for memory usage
//this version will also solve part 1
function getChecksum($input){
	while(strlen($input)%2 == 0){
		$checksum = "";
		for($i=0;$i<strlen($input);$i+=2){
			$checksum .= $input[$i] == $input[$i+1] ? "1" : "0";
		}
		$input = $checksum;
	}
	return $input;
}