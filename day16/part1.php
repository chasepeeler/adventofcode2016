<?php

$input = "01111001100111011";
$length = 272;
$current = $input;



do {
	$current = $current."0".getB($current);
	$step++;
} while(strlen($current) < $length);

echo getChecksum(substr($current,0,$length));



function getB($a){
	$b = $a;
	$b = strrev($a);
	$b = str_replace("0",".",$b);
	$b = str_replace("1","0",$b);
	$b = str_replace(".","1",$b);
	return $b;
}

function getChecksum($input){
	if(strlen($input)%2 != 0){
		return $input;
	} else {
		$pairs = str_split($input,2);
		$checksum = "";
		foreach($pairs as $pair){
			$checksum .= $pair[0] == $pair[1] ? "1" : "0";
		}
		return getChecksum($checksum);
	}
}