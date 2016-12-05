<?php

$id = "wtnhxymk";
$code = array_fill(0,8,"_");
$code_parts_found = 0;
$i = 0;
while($code_parts_found < 8){
	$hash = md5($id.$i);
	if(preg_match('/^00000(\d)(.)/',$hash,$m)){
		$position = $m[1];
		$code_part = $m[2];
		if($position >= 0 && $position < 8 && "_" == $code[$position]){
			$code_parts_found++;
			$code[$position] = $code_part;
			echo "Found another character, code is now: ".implode("",$code).PHP_EOL;
		}
		
	}
	$i++;
}

echo "The full code is ".implode("",$code).PHP_EOL;

