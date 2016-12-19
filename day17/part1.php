<?php

//this will store our node information
//which includes it's location ($x,$y) and
//the path to reach it.
//0,1 via D is a different node than 0,1 via DUD
class Node {
	
	public $x;
	public $y;
	public $pathToNode;
		
	public function __construct($x,$y,$pathToNode){
		$this->x = $x;
		$this->y = $y;
		$this->pathToNode =$pathToNode;
	}
	
	public function __toString(){
		return "{$this->x}x{$this->y}: {$this->pathToNode}";
	}
}

//we start a 0,0 with no path	
$start = new Node(0,0,"");

//queue up our starting node
$queue = [$start];

//this is our code
$code = "pvhmgsws";

const MAX_X = 3;
const MAX_Y = 3;

$target = [MAX_X,MAX_Y];
$shortest = null;

while($queue){
	
	//get the node in the queue
	$current = array_pop($queue);
	
	$x = $current->x;
	$y = $current->y;
	$path = $current->pathToNode;
	if([$x,$y] == $target){
		//see if this is the shortest path to the target
		if(empty($shortest) || strlen($path) < strlen($shortest)){
			$shortest = $path;
		}
		
		//once we get to 3x3, don't queue up any more nodes, since there is no reason to
		//go anywhere else...
		//but we do need to keep processing what is already in the queue in case
		//there is a shorter path to find.
		continue;
	}
	
	//if we can go left...
	if($x > 0 && !isLocked("L",$code.$path)){
		$queue[] = new Node($x-1,$y,$path."L");
	}
	
	//if we can go right
	if($x < MAX_X && !isLocked("R",$code.$path)){
		$queue[] = new Node($x+1,$y,$path."R");
	}
	
	//if we can go up
	if($y > 0 && !isLocked("U",$code.$path)){
		$queue[] = new Node($x,$y-1,$path."U");
	}
	
	//if we can go down
	if($y < MAX_Y && !isLocked("D",$code.$path)){
		$queue[] = new Node($x,$y+1,$path."D");
	}
	
}

echo "The shortest path is: ".$shortest.PHP_EOL;


function isLocked($dir,$code){
	$dirmap = ['U'=>0,'D'=>1,'L'=>2,'R'=>3];
	$hash = md5($code);
	$char = $hash[$dirmap[$dir]];
	return (is_numeric($char) || $char == 'a');
}

