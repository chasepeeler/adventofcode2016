<?php

if($argv[1] == "TEST"){
	$seed = 10;
	$dest_x = 7;
	$dest_y = 4;
	//we use this to draw our maze for demonstration purposes
	$maze_rows = 7;
	$maze_cols = 9;
} else {
	
	$seed = 1362;
	$dest_x = 31;
	$dest_y = 39;
	$maze_cols = $maze_rows = 41;
}
	
$start = [1,1];

$queue = [$start];
$visited = [];
$distances = [];
$distances[1][1] = 0;

$maze =[];

$path = [];
while($queue){
	
	//get the node in the queue
	$current = array_pop($queue);
	
	list($x,$y) = $current;

	$possibles = [];

	//nodes up, down, left, and right are all possible next steps
	//if x is 0, then left is not possible
	if($x > 0){
		$possibles[] = [$x-1,$y];
	}
	$possibles[] = [$x+1,$y];
	
	//if y is 0, then up is not possible
	if($y > 0){
		$possibles[] = [$x,$y-1];
	}
	$possibles[] = [$x,$y+1];
	
	
	
	foreach($possibles as $possible){
		list($_x,$_y) = $possible;
		//we can't visit this node if it's a wall
		if(!isWall($_x,$_y,$seed)){
			//distance is one more than the distance of "current"
			$distance = $distances[$x][$y] + 1;
			//if we don't have a distance OR this distance is less than a previously found distance,
			//then we should take this path
			if(!isset($distances[$_x][$_y]) || $distance < $distances[$_x][$_y]){
				$distances[$_x][$_y] = $distance;
				
				//save for drawing the map later
				$path[$_x.'x'.$_y] = [$x,$y];
				
				//queue this up so we can visit it's neighbors
				$queue[] = [$_x,$_y];
			}
		}

	}
}

buildMaze($maze,$maze_rows,$maze_cols,$seed);

$current = [$dest_x,$dest_y];
while($current && $current != [1,1]){
	$maze[$current[1]][$current[0]] = "0";
	$current = $path["{$current[0]}x{$current[1]}"];

}
$maze[$current[1]][$current[0]] = "0";
printMaze($maze);

echo $distances[$dest_x][$dest_y];






function buildMaze(&$maze,$rows,$cols,$seed){
	//since coordinates increase the X as they go right, we have to stored them backwards in our arrays
	for($i=0;$i<$cols;$i++){
		for($j=0;$j<$rows;$j++){
			$maze[$j][$i] = isWall($i,$j,$seed) ? "#" : ".";
		}
	}
}


function printMaze($maze){
	for($i=0;$i<count($maze);$i++){
		for($j=0;$j<count($maze[$i]);$j++){
			echo $maze[$i][$j];
		}
		echo PHP_EOL;
	}
}



function isWall($x,$y,$seed){
	$d = ($x*$x)+(3*$x)+(2*$x*$y)+$y+($y*$y)+$seed;
	$b = str_replace("0","",decbin($d));
	$f = strlen($b);
	
	return ($f%2 != 0);
}

