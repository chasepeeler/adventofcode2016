<?php




$input = array_map("str_split",array_map("trim",file(__DIR__."/input.txt")));
$distances = [];
$points = [];

for($row=0;$row<count($input);$row++){
	for($col=0;$col<count($input[$row]);$col++){
		$cell = $input[$row][$col];
		if(is_numeric($cell)){
			$points[$cell] = [$row,$col];
		}
	}
}

//treat our "points" as nodes on a graph, and find the shortest distance from each node to all other nodes
//this takes a while to run, so, we'll cache the data so we can quickly run it after making tweaks to the second part
//without having to make all of these calculations again
//to run the searches again, just delete the cache.txt file.
if(!file_exists(__DIR__."/cache.txt")){
	$all_nodes = [];
	for($i=0;$i<count($points);$i++){
		for($j=0;$j<$i;$j++){
			$distances['n'.$i]['n'.$j] = $distances['n'.$j]['n'.$i];
			$distance_map['n'.$i][$distances['n'.$j]['n'.$i]][] = 'n'.$j;
			$distance_map['n'.$i][$distances['n'.$j]['n'.$i]] = array_unique($distance_map['n'.$i][$distances['n'.$j]['n'.$i]]);
		}
		for($j=$i+1;$j<count($points);$j++){
			$p1 = $points[$i];
			$p2 = $points[$j];
			$d = shortestDistance($input,$points,$p1,$p2);
			$distances['n'.$i]['n'.$j] = $d;
			$distance_map['n'.$i][$d][] = 'n'.$j;
			$distance_map['n'.$i][$d] = array_unique($distance_map['n'.$i][$d]);
		}
		$all_nodes["n{$i}"] = true;
		
	}

	$cache = ["distances"=>$distances,"all_nodes"=>$all_nodes,"distance_map"=>$distance_map];
	file_put_contents(__DIR__."/cache.txt",serialize($cache));
} else {
	$cache = unserialize(file_get_contents(__DIR__."/cache.txt"));
	$distances = $cache['distances'];
	$all_nodes = $cache['all_nodes'];
	$distance_map = $cache['distance_map'];
}

//The data found in the previous step gives us a fully connected graph. Now, we just need to find the shortest path
//that visits all of the nodes. The idea of "revisiting" a node doesn't exist anymore, since we can go directly from one node
//to another node. Revisiting just means we didn't have to find a shortest path that didn't possibly pass over another node

$distance = findDistance('n0',$all_nodes,$distances);

echo $distance;

function findDistance($node,$remaining,$all_distances){
	unset($remaining[$node]);
	
	if(empty($remaining)){
		//no where else to go, so return 0
		return 0;
	}
	
	$min_distance = PHP_INT_MAX;

	//find the shortest distance from the next node to all remaining nodes
	foreach($all_distances[$node] as $next_node=>$distance){
		if($remaining[$next_node]){
			$d = $distance + findDistance($next_node,$remaining,$all_distances);
			if($d < $min_distance){
				$min_distance = $d;
			}
		}
	}

	return $min_distance;
	
}






function shortestDistance($maze,$points,$p1,$p2){
	
	$queue = [$p1];
	$distances = [];
	
	while($queue){
	
		//get the node in the queue
		$current = array_pop($queue);
	
		list($row,$col) = $current;

		$possibles = [];

		if(array_key_exists($row+1,$maze) && array_key_exists($col,$maze[$row+1]) && $maze[$row+1][$col] != "#"){
			$possibles[] = [$row+1,$col];
		}
		
		if(array_key_exists($row-1,$maze) && array_key_exists($col,$maze[$row-1]) && $maze[$row-1][$col] != "#"){
			$possibles[] = [$row-1,$col];
		}
		
		if(array_key_exists($col+1,$maze[$row]) && $maze[$row][$col+1] != "#"){
			$possibles[] = [$row,$col+1];
		}
		
		if(array_key_exists($col-1,$maze[$row]) && $maze[$row][$col-1] != "#"){
			$possibles[] = [$row,$col-1];
		}
		
	
		foreach($possibles as $possible){
			list($_row,$_col) = $possible;
			//distance is one more than the distance of "current"
			$distance = $distances[$row][$col] + 1;
			//if we don't have a distance OR this distance is less than a previously found distance,
			//then we should take this path
			if(!isset($distances[$_row][$_col]) || $distance < $distances[$_row][$_col]){
				$distances[$_row][$_col] = $distance;
				
				//queue this up so we can visit it's neighbors
				if($possible != $p2){
					$queue[] = [$_row,$_col];
				}
			}
		}
	}
	return $distances[$p2[0]][$p2[1]];
}
