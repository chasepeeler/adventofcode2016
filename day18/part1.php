<?php
//allow input on command line so this can solve for part1 and part2
$max_rows = $argv[1];

//we don't need to keep an array of the entire floor, just the current row and previous row
//and we count the safe tiles as we go
$curr_row = str_split(".^^.^^^..^.^..^.^^.^^^^.^^.^^...^..^...^^^..^^...^..^^^^^^..^.^^^..^.^^^^.^^^.^...^^^.^^.^^^.^.^^.^.");
$safe_tiles = 0;

//get safe tiles on first row
$safeTiles = countSafe($curr_row);

for($i=1;$i<$max_rows;$i++){
	//make current row our previous row
	$prev_row = $curr_row;
	
	//make our current row empty
	$curr_row = [];
	
	//build our current row
	for($j=0;$j<count($prev_row);$j++){
		
		
		if(array_key_exists($j-1,$prev_row)){
			$t1 = $prev_row[$j-1];
		} else {
			//we're at the left wall, so the left tile is safe
			$t1 = ".";
		}
		$t2 = $prev_row[$j];
		if(array_key_exists($j+1,$prev_row)){
			$t3 = $prev_row[$j+1];
		} else {
			//we're at the right wall, so the right tile is safe
			$t3 = ".";
		}
		
		$curr_row[$j] = safeOrTrap($t1,$t2,$t3);
	}
	//determine the number of safe tiles for the row we just built
	$safeTiles += countSafe($curr_row);
}

echo "There are {$safeTiles} safe tiles in {$max_rows} rows".PHP_EOL;

function countSafe($row){
	$s = 0;
	foreach($row as $tile){
		if($tile == "."){
			$s++;
		}
	}
	return $s;
}

function safeOrTrap($left,$center,$right){
	if($left == "^" && $center=="^" && $right=="."){
		return "^";
	}
	if($left == "." && $center == "^" && $right=="^"){
		return "^";
	}
	if($left == "^" && $center == "." && $right=="."){
		return "^";
	}
	if($left == "." && $center == "." && $right=="^"){
		return "^";
	}
	return ".";
}
