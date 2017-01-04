<?php

class Node {
	public $x;
	public $y;
	public $size;
	public $used;
	public $avail;
	public $usedp;
	
}

$input = array_map("trim",file(__DIR__."/input.txt"));
//get rid of first two lines that aren't needed
array_shift($input);
array_shift($input);

$regex = '@/dev/grid/node-x(\d+)-y(\d+)\s+(\d+)T\s+(\d+)T\s+(\d+)T\s+(\d+)%@';


$nodes = [];

$emptyNode = null;
//put our information into a nice array to make it easier to deal with
foreach($input as $line){
	preg_match($regex,$line,$m);
	$node = new Node();
	list(,$node->x,$node->y,$node->size,$node->used,$node->avail,$node->usedp) = $m;
	$nodes[$node->x][$node->y] = $node;
	if($node->used == 0){
		$emptyNode = $node;
	}
}

//first, we need to get the target data into our empty node, so, we can think of this as finding the shortest
//path from the empty node to the target data node. We want to get the "hole" to the spot above our target node
$queue = [$emptyNode];
$distances = [];
while($queue){
	
	$node = array_pop($queue);
	
	$x = $node->x;
	$y = $node->y;
	if($x == count($nodes)-2 && $y == 0){
		continue;
	}
	$possibles = [];
	if(array_key_exists($x+1,$nodes) && array_key_exists($y,$nodes[$x+1])){
		$possibles[] = $nodes[$x+1][$y];
	}
	if(array_key_exists($x-1,$nodes) && array_key_exists($y,$nodes[$x-1])){
		$possibles[] = $nodes[$x-1][$y];
	}
	
	if(array_key_exists($y+1,$nodes[$x])){
		$possibles[] = $nodes[$x][$y+1];
	}
	
	if(array_key_exists($y-1,$nodes[$x])){
		$possibles[] = $nodes[$x][$y-1];
	}

	foreach($possibles as $possible){
		if($possible->used <= $node->size){
			$distance = $distances[$x][$y] + 1;
			//if we don't have a distance OR this distance is less than a previously found distance,
			//then we should take this path
			if(!isset($distances[$possible->x][$possible->y]) || $distance < $distances[$possible->x][$possible->y]){
				$distances[$possible->x][$possible->y] = $distance;
				
				//queue this up so we can visit it's neighbors
				$queue[] = $possible;
			}
		}
	}
	
}

$distance = $distances[count($nodes)-2][0];
//and now we add one more step for getting swappping the target data and the empty node,
//putting our empty node in our original target
$distance++;

//at this point, we need to just move our data straight up (assuming we don't hit any walls)
//as long as we don't hit a wall, it takes 5 moves to get the data up one row
// a  b     a b   a b     a _    _ a     t a
// t  c     t c   t _     t b    t b     _ b
// _  d     d _   d c     d c    d c     c d

//For my input, I can be sure this will always have no walls, so, 
//we just need to multiple the number of rows to move it up by 5
//since we start our counting from 0, our number of rows is just the x position of our target data

$distance += (count($nodes)-2)*5;

echo "Minimum steps: ".$distance;







