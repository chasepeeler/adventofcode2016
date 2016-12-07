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
		$input = str_replace($bracket,"|",$input); //makes the check of the parts outside the bracket a bit easier
	}
	
	if(hasSequence($input)){
		$tls++;
	}
	
	
}

echo "There are {$tls} IPs with TLS".PHP_EOL;

function hasSequence($str){
	
	$strings = explode("|",$str);
	foreach($strings as $string){
		$string = str_replace("[","",$string);
		$string = str_replace("]","",$string);
		$string = str_split($string);
		for($i=0;$i<count($string);$i++){
			$s1 = $string[$i];
			$s2 = $string[$i+1]?:"";
			$s3 = $string[$i+2]?:"";
			$s4 = $string[$i+3]?:"";
			if($s1 == $s4 && $s2 == $s3 && $s1 != $s2){
				return true;
			}
		}
	}
	
	return false;
}