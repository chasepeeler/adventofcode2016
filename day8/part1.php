<?php


$row = array_fill(0,50,".");
$display = array_fill(0,6,$row);


$commands = array_map("trim",file(__DIR__."/input.txt"));


$rect_cmd = '/rect (\d+)x(\d+)/';
$rotate_col_cmd = '/rotate column x=(\d+) by (\d+)/';
$rotate_row_cmd = '/rotate row y=(\d+) by (\d+)/';

foreach($commands as $command){
	if(preg_match($rect_cmd,$command,$m)){
		for($i=0;$i<$m[2];$i++){
			for($j=0;$j<$m[1];$j++){
				$display[$i][$j] = "#";
			}
		}
	} elseif(preg_match($rotate_row_cmd,$command,$m)){
		rotateRow($display,$m[1],$m[2]);
	} elseif(preg_match($rotate_col_cmd,$command,$m)){
		rotateCol($display,$m[1],$m[2]);
	} else {
		echo $command." is not valid!!".PHP_EOL;
	}
}

echo printDisplay($display);
echo "There are ".(countLit($display))." pixels lit.".PHP_EOL;


function printDisplay($display){
	for($i=0;$i<count($display);$i++){
		for($j=0;$j<count($display[$i]);$j++){
			echo $display[$i][$j];
		}
		echo PHP_EOL;
	}
	echo PHP_EOL.PHP_EOL;
}

function rotateCol(&$array,$col,$num){
	//arrays are easy to deal with when it comes to row operations, but not so much with column operations
	//so, in order to make this easier, we'll transpose the array,
	//operate on the column as a row
	//and then transpose it back
	$array = transposeArray($array);
	rotateRow($array,$col,$num);
	$array = transposeArray($array);
}

function rotateRow(&$array,$row,$num){
	$new_row = [];

	//make our rotation < the size of our row. E.g. Rotate of 51 is the same as a rotation of 1, 149 is the same as 49
	$num = $num%count($array[$row]);

	//rotating by X just means we put the item currently in position 0 into position X, in position 1 into position X+1, etc.
	//when X reaches the maximum index, set it back to 0.
	for($i=0;$i<count($array[$row]);$i++){
		//wrap back around to the beginning when we get to the end of the array
		if($num == count($array[$row])){
			$num = 0;
		}
		$new_row[$num] = $array[$row][$i];
		$num++;
	}
	//replace the original row with the new row
	$array[$row] = $new_row;
}

function transposeArray($array){
	$new = [];
	for($i=0;$i<count($array);$i++){
		for($j=0;$j<count($array[$i]);$j++){
			$new[$j][$i] = $array[$i][$j];
		}
	}
	return $new;
}


function countLit($display){
	$lit = 0;
	for($i=0;$i<count($display);$i++){
		for($j=0;$j<count($display[$i]);$j++){
			$lit += intval($display[$i][$j] == "#");
		}
	}
	return $lit;
}
