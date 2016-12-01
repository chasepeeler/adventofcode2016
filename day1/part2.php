<?php
//This modifies part 1 to solve for part 2. It will still solve part 1 as well.

//since we'll be storing the coordinates of each place we visit in a 2D array
//this will allow us to use the constant to determine which index to update
const MOD_X = 0;
const MOD_Y = 1;

//we'll multiply our distance by this before we add it
//easier than using an IF statement and then deciding whether or add or subtract
//e.g. if(INC){ $x += 1} else {$x -= 1}
const INC = 1;
const DEC = -1;

//our cardinal directions. First element represents whether we modify the X or Y coordinate, second indicates
//whether we add or subtract the distance travelled.
//using variable instead of constants because I'm running this against PHP 5.5
$NORTH = [MOD_X,INC];
$SOUTH = [MOD_X,DEC];
$WEST = [MOD_Y,DEC];
$EAST = [MOD_Y,INC];

//we start at 0,0 facing north
$direction = $NORTH;
$current = [0,0];

$instructions_string = "L1, L3, L5, L3, R1, L4, L5, R1, R3, L5, R1, L3, L2, L3, R2, R2, L3, L3, R1, L2, R1, L3, L2, R4, R2, L5, R4, L5, R4, L2, R3, L2, R4, R1, L5, L4, R1, L2, R3, R1, R2, L4, R1, L2, R3, L2, L3, R5, L192, R4, L5, R4, L1, R4, L4, R2, L5, R45, L2, L5, R4, R5, L3, R5, R77, R2, R5, L5, R1, R4, L4, L4, R2, L4, L1, R191, R1, L1, L2, L2, L4, L3, R1, L3, R1, R5, R3, L1, L4, L2, L3, L1, L1, R5, L4, R1, L3, R1, L2, R1, R4, R5, L4, L2, R4, R5, L1, L2, R3, L4, R2, R2, R3, L2, L3, L5, R3, R1, L4, L3, R4, R2, R2, R2, R1, L4, R4, R1, R2, R1, L2, L2, R4, L1, L2, R3, L3, L5, L4, R4, L3, L1, L5, L3, L5, R5, L5, L4, L2, R1, L2, L4, L2, L4, L1, R4, R4, R5, R1, L4, R2, L4, L2, L4, R2, L4, L1, L2, R1, R4, R3, R2, R2, R5, L1, L2";

$instructions = array_map("trim",explode(",",$instructions_string));

//since we're going to cache our visited spots AFTER we move, go ahead and cache our starting point now
$visited = [$current];

//we'll use this to keep track of every point we visit more than once. Then we just take the first one to 
//figure out which one was visited more than once first.
$multiple_visits = [];
foreach($instructions as $instruction)
{
	preg_match('/([LR])(\d+)/',$instruction,$m);
	$dir = $m[1];
	$dist = $m[2];
	
	
	$direction = change_direction($direction,$dir);
	echo "Going {$dist} blocks ".get_direction_name($direction).PHP_EOL;
	
	$index = $direction[0];

	//visited isn't limited to just the locations where you change directions,
	//but every location you walk through as well,
	//so, we need to change how we calculate our movement to modify our coordinate by one block
	//at a time, instead of one trip at a time
	for($i=0;$i<$dist;$i++){
		//increase or decrease by 1
		$current[$index] += $direction[1];
		
		//if we've visited in before, cache it in the multiple_visits array.
		//technically we can stop after the first one, but, no reason to do the extra checks
		//to stop caching the additional records
		if(in_array($current,$visited)){
			$multiple_visits[] = $current;
			
			//technically we could stop here in order to solve part 2, but since I'd like the solution to still
			//give an answer for part 1, we'll keep going
		}
		
		//put this in our visited bucket. See above comments for why its easier to just keep going even after we get
		//the information we need
		$visited[] = $current;	
	}
	
}

$total_distance = abs($current[0])+abs($current[1]);

echo "Total distance is {$total_distance} blocks".PHP_EOL;

$visited_twice_distance = abs($multiple_visits[0][0])+abs($multiple_visits[0][1]);

echo "Total distance to first location visited twice is {$visited_twice_distance} blocks".PHP_EOL;

//determines our new direction based on our current direction and which way we are turning
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

//outputs a direction name, used for informational messages
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



