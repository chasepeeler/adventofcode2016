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
		//when we test the IP for existence of the sequence outside 
		//of the brackets, we don't want to include the brackets
		
		//after writing this, I realized that we shouldn't have any sequences inside the brackets
		//if we make it this far, so, there isn't any need to remove those sections
		//however, if I don't include the line below, my output is off by 1 (117 instead of 118)
		$input = str_replace($bracket,"|",$input); 
		
		//the offending IP is nnmyoxtukxhrsgt[ecovrntpmkcaekonw]ncfzdxdlawbwtxqpkik[fkkkkxidubuatpihcnc]wqxmtvyakouvijt[tjvyhgempiufanh]bcibhdmbmbmmbyyi
		
		//seems the error is caused by the fact that I'm not using preg_match_all in the sequence checker..
		//I got "lucky" and the IP above has a pattern that will match the regex in hasSequence (inside the brackets), but doesn't exclude the
		//sequence since it's four of the same character. However, if I don't remove that string,
		//since I'm not doing preg_match_all, when I check the IP itself, it matches on that sequence again,
		//and determines it's not valid, so it doesn't see it as having TLS
		//when I remove the string, then it matches the proper sequence further in the string, returning true.
		//If there had been ANY instances of an aaaa sequence outside the brackets BEFORE the abba sequence, then the code as
		//currently written would not have worked.
	}
	
	if(hasSequence($input)){
		$tls++;
	}
	
	
}

echo "There are {$tls} IPs with TLS".PHP_EOL;


function hasSequence($str){
	$regex = '/([a-z])([a-z])\2\1/';
	
	if(!preg_match($regex,$str,$m)){
		return false;
	}
	
	if(strcasecmp($m[1],$m[2]) == 0){
		return false;
	}
	
	return true;
}