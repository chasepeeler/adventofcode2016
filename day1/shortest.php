<?php


const MOD_X = 0;
const MOD_Y = 1;

const INC = 1;
const DEC = -1;

$NORTH = [MOD_X,INC];
$SOUTH = [MOD_X,DEC];
$WEST = [MOD_Y,DEC];
$EAST = [MOD_Y,INC];

$direction = $NORTH;
$start = [0,0];

$instructions_string = "L1, L3, L5, L3, R1, L4, L5, R1, R3, L5, R1, L3, L2, L3, R2, R2, L3, L3, R1, L2, R1, L3, L2, R4, R2, L5, R4, L5, R4, L2, R3, L2, R4, R1, L5, L4, R1, L2, R3, R1, R2, L4, R1, L2, R3, L2, L3, R5, L192, R4, L5, R4, L1, R4, L4, R2, L5, R45, L2, L5, R4, R5, L3, R5, R77, R2, R5, L5, R1, R4, L4, L4, R2, L4, L1, R191, R1, L1, L2, L2, L4, L3, R1, L3, R1, R5, R3, L1, L4, L2, L3, L1, L1, R5, L4, R1, L3, R1, L2, R1, R4, R5, L4, L2, R4, R5, L1, L2, R3, L4, R2, R2, R3, L2, L3, L5, R3, R1, L4, L3, R4, R2, R2, R2, R1, L4, R4, R1, R2, R1, L2, L2, R4, L1, L2, R3, L3, L5, L4, R4, L3, L1, L5, L3, L5, R5, L5, L4, L2, R1, L2, L4, L2, L4, L1, R4, R4, R5, R1, L4, R2, L4, L2, L4, R2, L4, L1, L2, R1, R4, R3, R2, R2, R5, L1, L2";

$instructions = array_map("trim",explode(",",$instructions_string));

foreach($instructions as $instruction)
{
	preg_match('/([LR])(\d+)/',$instruction,$m);
	$dir = $m[1];
	$dist = $m[2];
	
	echo $dir.' '.$dist.PHP_EOL;
	
	$direction = change_direction($direction,$dir);
	echo "Going {$dist} blocks ".get_direction_name($direction).PHP_EOL;
	
	$index = $direction[0];
	$dist *= $direction[1];
	
	$start[$index] += $dist;
	
}

$total_distance = abs($start[0])+abs($start[1]);

echo "Total distance is {$total_distance} blocks".PHP_EOL;

function change_direction($current_dir,$turn){
	global $NORTH,$SOUTH,$EAST,$WEST;
	
	if($current_dir == $NORTH && $turn == "L"){
		return $WEST;
	}
	if($current_dir == $NORTH && $turn == "R"){
		return $EAST;
	}
	
	if($current_dir == $SOUTH && $turn == "L"){
		return $EAST;
	}
	if($current_dir == $SOUTH && $turn == "R"){
		return $WEST;
	}
	
	if($current_dir == $EAST && $turn == "L"){
		return $NORTH;
	}
	if($current_dir == $EAST && $turn == "R"){
		return $SOUTH;
	}
	
	if($current_dir == $WEST && $turn == "L"){
		return $SOUTH;
	}
	if($current_dir == $WEST && $turn == "R"){
		return $NORTH;
	}
	
}

function get_direction_name($dir){
	global $NORTH,$SOUTH,$EAST,$WEST;
	if($dir == $NORTH){
		return "NORTH";
	}
	if($dir == $SOUTH){
		return "SOUTH";
	}
	if($dir == $EAST){
		return "EAST";
	}
	if($dir == $WEST){
		return "WEST";
	}
}



