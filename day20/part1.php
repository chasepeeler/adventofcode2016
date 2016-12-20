<?php

$input = array_map("trim",file(__DIR__."/input.txt"));

$provisional = [];
foreach($input as $range){
	list($min,$max) = explode("-",$range);
	
	//we can build an initial list of provisional IPs
	//by getting all of the IPs before the start of a blocked range
	//or after the end of a blocked range
	
	//we don't want IPs less than 0
	if(bccomp($min,"0") > 0){
		$provisional[] = bcsub($min,1);
	}
	$provisional[] = bcadd($max,"1");
	
}

//now we need to go through all of the provisional IPs
//and get rid of any that are blocked by one of the ranges,
//since our provisional list of IPs only pulled in those not blocked by ONE range
$lowest = null;
while($provisional){
	
	$ip = array_pop($provisional);
	if($lowest !== null && bccomp($ip,$lowest) > 0){
		continue; //no need to check if we already know it wont be the lowest.
	}
	$blocked = false;
	foreach($input as $range){

		list($min,$max) = explode("-",$range);
		
		if(bccomp($ip,$min) >= 0 && bccomp($ip,$max) <=0){
			//this IP is within the range, so it's blocked
			$blocked = true;
			
			//we also don't need to keep checking if its blocked by any other ranges
			//once blocked, always blocked
			break;
		}
	}
	//if it stays, put it in our REAL allowed queue
	if(!$blocked){
		//we know it's the lowest because we wouldn't be checking it if it weren't
		$lowest = $ip;
	}
}


echo "Lowest allowed is ".$lowest;
