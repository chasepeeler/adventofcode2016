<?php

$salt = "jlmsuwbz";

$current = 0;
$hashes = [];
$keys = [];


while(count($keys) < 64){
	//since we're caching hashes, we use a function that will produce the hash if it doesn't exist
	$hash = getHash($hashes,$current);

	//check for a triple
	if(preg_match('/(.)\1\1/',$hash,$m)){
		//now we need to check the next 1000 hashes for a quintuple
		for($i=$current+1;$i<$current+1000;$i++){
			//again, use our method to pull from the cache, or generate if it doesn't exist
			$nextHash = getHash($hashes,$i,$salt);
			
			//check for a quintuple of the same character found in our triple
			if(preg_match('/'.$m[1].'{5}/',$nextHash)){
				$keys[] = $hash;
				echo "Found key {$hash} at index {$current}".PHP_EOL;
				//no reason to keep looking at the remaining hashes in the block of 1000
				break;
			}
		}
	}
	//we are caching or hashes so we don't have to recreate them,
	//but, once we pass an index, we don't need that hash anymore
	//so we can clear it out in order to save memory.
	clearOldHashes($hashes,$current);
	
	//increase our pointer to in order to properly calculate/pull the next hash
	$current++;
}



function getHash(&$hashes,$current,$salt){
	//see the clearOldHashes method for the reason we use prefix the index with a letter.
	if(!array_key_exists("i".$current,$hashes)){
		
		//add in loop to implement our key-stretching algorithm
		$hash = $salt.$current;
		for($i=0;$i<2017;$i++){
			$hash = strtolower(md5($hash));
		}
		$hashes["i".$current] = $hash;
	}
	return $hashes["i".$current];
}


function clearOldHashes(&$hashes,$current){
	//make sure we're pointing at the start of the array
	reset($hashes);
	while(!empty($hashes) && str_replace("i","",key($hashes)) < $current){
		//array shift will reindex a numeric array, but since we've put a character in front of it
		//the keys are preserved
		array_shift($hashes);
		
		//might not be necessary, but doing it anyway
		reset($hashes);
	}
}