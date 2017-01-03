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

//put our information into a nice array to make it easier to deal with
foreach($input as $line){
	preg_match($regex,$line,$m);
	$node = new Node();
	list(,$node->x,$node->y,$node->size,$node->used,$node->avail,$node->usedp) = $m;
	$nodes[] = $node;
}

$viable_pairs = [];
foreach($nodes as $nodea){
	foreach($nodes as $nodeb){
		if($nodea == $nodeb){
			continue;
		}
		
		if(in_array([$nodea,$nodeb],$viable_pairs)){
			continue;
		}
		
		if($nodea->used == 0){
			continue;
		}
		
		if($nodea->used <= $nodeb->avail){
			$viable_nodes[] = [$nodea,$nodeb];
		}
	}
}


echo "There are ".count($viable_nodes)." viable pairs".PHP_EOL;

