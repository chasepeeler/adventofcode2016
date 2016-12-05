<?php

$id = "wtnhxymk";
$code = "";
$i = 0;
while(strlen($code) < 8){
	$hash = md5($id.$i);
	if(preg_match('/^00000(.)/',$hash,$m)){
		$code .= $m[1];
		echo "Found another character, code is now: ".$code.PHP_EOL;
	}
	$i++;
}

echo "The full code is ".$code.PHP_EOL;

