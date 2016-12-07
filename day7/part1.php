<?php

//get our IPs into an array, 1 IP per row
$inputs = file(__DIR__."/input.txt");

//test input
//$inputs = ["abba[mnop]qrst","abcd[bddb]xyyx","aaaa[qwer]tyui","ioxxoj[asdfgh]zxcvbn"];

$tls = 0;

for($i=0;$i<count($inputs);$i++){
	$input = $inputs[$i];
	
	//this will extract all of the bracketed sections
	preg_match_all('/\[.+?\]/',$input,$brackets);
	
	foreach($brackets[0] as $bracket){
		//if our abba sequence appears inside any of the bracketed sections, then the IP doesn't
		//support TLS, so we can go to the next IP
		if(hasSequence($bracket)){
			continue 2; //continue 2 to skip to the next item in the outer loop
		}
	}
	
	if(hasSequence($input)){
		$tls++;
	}
	
	
}

echo "There are {$tls} IPs with TLS".PHP_EOL;

function hasSequence($str){
	$regex = '/([a-z])([a-z])\2\1/';

	
	if(!preg_match_all($regex,$str,$m,PREG_SET_ORDER)){
		//if there is no match, the sequence doesn't exist
		return false;
	}
	
	//matches could be aaaa or abba, so we need to compare our letters
	foreach($m as $n){
		if(strcasecmp($n[1],$n[2]) != 0){
			//if any are abba, then it has the sequence
			return true;
		}
	}
	//doesn't have the sequence, all matches must have been aaaa
	return false;
}