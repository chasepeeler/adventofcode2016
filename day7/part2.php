<?php

//get our IPs into an array, 1 IP per row
$inputs = array_map("trim",file(__DIR__."/input.txt"));

//test input
//$inputs = ["aba[bab]xyz","xyx[xyx]xyx","aaa[kek]eke","zazbz[bzb]cdb"];

$ssl = 0;

for($i=0;$i<count($inputs);$i++){
	$input = $inputs[$i];
	
	//this will extract all of the bracketed sections
	preg_match_all('/\[.+?\]/',$input,$brackets);
	
	$bracket_sequences = [];
	foreach($brackets[0] as $bracket){
		$bracket_sequences = array_merge($bracket_sequences,getSequences($bracket));
		$input = str_replace($bracket,"|",$input);
	}
	
	if(empty($bracket_sequences)){
		continue;
	}
	
	$supernet_sequences = getSequences($input);
	if(empty($supernet_sequences)){
		continue;
	}
	
	
	for($k=0;$k<count($supernet_sequences);$k++){
		for($j=0;$j<count($bracket_sequences);$j++){
			if(corresponds($supernet_sequences[$k],$bracket_sequences[$j])){
				$ssl++;
				continue 3;
			}
		}
	}
}

echo "There are {$ssl} IPs with SSL:".PHP_EOL;

function corresponds($a,$b){
	return (substr($a,0,1) == substr($b,1,1) && substr($a,1,1) == substr($b,0,1));
	
}

function getSequences($str){
	//we can't just use a regular regex anymore, since 
	//since an aba could overlap a previous aba
	//I guess this was true in part 1 as well, since abbaab could have occured
	
	$s = [];
	$strings = explode("|",$str);
	foreach($strings as $string){
		$string = str_replace("[","",$string);
		$string = str_replace("]","",$string);
		$string = str_split($string);
		for($i=0;$i<count($string);$i++){
			$s1 = $string[$i];
			$s2 = $string[$i+1]?:"";
			$s3 = $string[$i+2]?:"";
			if($s1 == $s3 && $s1 != $s2){
				$s[] = $s1.$s2.$s3;
			}
		}
	}
	return $s;
}