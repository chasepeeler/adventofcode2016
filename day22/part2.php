<?php

class Node {
	public $x;
	public $y;
	public $size;
	public $used;
	public $avail;
	public $usedp;

	public function getFreeNeighbors($nodes){
		$n = [];
		$x = $this->x;
		$y = $this->y;
		$keys = [($x+1).'x'.$y,($x-1).'x'.$y,$x.'x'.($y+1),$x.'x'.($y-1)];
		
		foreach($keys as $key){
			if(array_key_exists($key,$nodes) && $nodes[$key]->canTakeDataFrom($this)){
				$n[] = $nodes[$key];
			}
		}
		return $n;
	}
	
	public function getFullNeighbors($nodes){
		$n = [];
		$x = $this->x;
		$y = $this->y;
		$keys = [($x+1).'x'.$y,($x-1).'x'.$y,$x.'x'.($y+1),$x.'x'.($y-1)];
		
		foreach($keys as $key){
			if(array_key_exists($key,$nodes) && !$nodes[$key]->canTakeDataFrom($this)){
				$n[] = $nodes[$key];
			}
		}
		return $n;
	}
	
	public function canTakeDataFrom($node){
		echo $node->getId().' '.$this->getId().PHP_EOL;
		echo $node->used.' '.$this->avail.PHP_EOL;
		return $this->avail >= $node->used;
	}
	
	public function moveDataTo($node){
		$node->used += $this->used;
		$node->avail -= $this->used;
		$this->used = 0;
		$this->avail = $this->size;
	}
	
	public function getId(){
		return $this->x.'x'.$this->y;
	}
}

class State {
	public $nodes;
	public $currentNodeId;
	public $prevState;
	public $nextStates = [];
	public $distance;
	
	
	public function getCopy(){
		$s = new State();
		foreach($this->nodes as $k=>$n){
			$s->nodes[$k] = clone $n;
		}
		return $s;
	}
	
	public function getAvailableNodesFromCurrent(){
		$n = [];
		$curr = $this->nodes[$this->currentNodeId];
		foreach($this->nodes as $node){
			if($curr == $node){
				continue;
			}
			if($node->canTakeDataFrom($curr)){
				$n[] = $node;
			}
		}
		return $n;
	}
}
	


$input = array_map("trim",file(__DIR__."/sample_input.txt"));
//get rid of first two lines that aren't needed
array_shift($input);
array_shift($input);

$regex = '@/dev/grid/node-x(\d+)-y(\d+)\s+(\d+)T\s+(\d+)T\s+(\d+)T\s+(\d+)%@';


$nodes = [];
$max_x = 0;
$max_y = 0;
$min_distance = PHP_INT_MAX;


//put our information into a nice array to make it easier to deal with
foreach($input as $line){
	preg_match($regex,$line,$m);
	$node = new Node();
	list(,$node->x,$node->y,$node->size,$node->used,$node->avail,$node->usedp) = $m;
	$nodes[$node->x.'x'.$node->y] = $node;
	$max_x = max($node->x,$max_x);
	$max_y = max($node->y,$max_y);
}


$state = new State();
$state->nodes = $nodes;

$root_state = $state;
$state->currentNodeId = $max_x.'x0';
$state->distance = 0;
$queue = [$state];


while($queue){
	
	$state = array_pop($queue);
	if($state->currentNodeId == "0x0"){
		$min_distance = min($min_distance,$state->distance);
		continue;
	}
	$nodes = $state->nodes;
	$node = $nodes[$state->currentNodeId];
	$neighbors = $node->getNeighbors($nodes);
	print_r($neighbors);exit;
	foreach($neighbors as $neighbor){
		$next_state = $state->getCopy();
		$next_state->distance = $state->distance + 1;
		$next_state->nodes[$state->currentNodeId]->moveDataTo($next_state->nodes[$neighbor->getId()]);
		$queue[] = $state;
	}
}



echo "The minimum number of moves required to access the data is ".$min_distance.PHP_EOL;


