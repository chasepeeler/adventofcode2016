<?php

$input = array_map("trim",file(__DIR__."/input.txt"));

$command_map = [];
$command_map['swapPos'] = '/swap position (\d+) with position (\d+)/';
$command_map['swapLetter'] = '/swap letter ([a-z]) with letter ([a-z])/';
$command_map['rotateLR'] = '/rotate (left|right) (\d+) step/';
$command_map['rotatePos'] = '/rotate based on position of (letter) ([a-z])/'; //capturing two items so we can have a uniform $a,$b parameter set at indexes 1 and 2
$command_map['reverse'] = '/reverse positions (\d+) through (\d+)/';
$command_map['move'] = '/move position (\d+) to position (\d+)/';

/* 
//test input
$input = [];
$input[] = "swap position 4 with position 0";
$input[] = "swap letter d with letter b";
$input[] = "reverse positions 0 through 4";
$input[] = "rotate left 1 step";
$input[] = "move position 1 to position 4";
$input[] = "move position 3 to position 0";
$input[] = "rotate based on position of letter b";
$input[] = "rotate based on position of letter d";
*/

$password = "abcdefgh";

foreach($input as $command){
	foreach($command_map as $func=>$regex){
		if(preg_match($regex,$command,$m)){
			$func($password,$m[1],$m[2]);
			continue 2; //no need to keep checking if it matches the other commands
		}
	}
	echo "Invalid command! {$command}".PHP_EOL;
	exit(1);
}

echo "Shuffled password is ".$password.PHP_EOL;

function swapPos(&$string,$a,$b){
	$temp = $string[$a];
	$string[$a] = $string[$b];
	$string[$b] = $temp;
}

function swapLetter(&$string,$a,$b){
	$string = str_replace($a,".",$string);
	$string = str_replace($b,$a,$string);
	$string = str_replace(".",$b,$string);
}

function rotateLR(&$string,$dir,$steps){
	$newString = "";
	$steps = $steps%strlen($string); //a string of length 5 with a rotation of 6, is really a rotation of 1
	if($dir == 'right'){
		//instead of dealing with whether to increment or decrement our indexes, just reverse the string, and then treat
		//it the same as a left rotation
		$string = strrev($string);
	}
	//rotating left by X steps just means that the new string will start
	//with the character at position X, and one we reach the end, we go back to the start
	//0123
	//abcd
	//rotated left 2 times
	//cdab
	for($i=0,$j=$steps;$i<strlen($string);$i++,$j++){
		if($j == strlen($string)){
			$j = 0;
		}
		$newString .= $string[$j];
	}
	if($dir == 'right'){
		//undo our reverse
		$string = strrev($newString);
	} else {
		$string = $newString;	
	}
}

function rotatePos(&$string,$unused,$letter){
	//the position of the letter determines how many steps we take,
	//and we always rotate right.
	//so, determine how many steps, then use our rotateLR function
	$pos = strpos($string,$letter);
	$steps = 1 + $pos;
	if($pos >= 4){
		$steps++;
	}
	rotateLR($string,"right",$steps);
}

function reverse(&$string,$a,$b){
	if($a > 0){
		$s1 = substr($string,0,$a); //length from 0 to X-1 is X...
	} else {
		$s1 = "";
	}
	$s2 = substr($string,$a,$b-$a+1); //since we need to include index b, we have to add one to the difference. 
									  //indexes 1 - 3 contain three characters, but 3-1 = 2.
	if($b == (strlen($string)-1)){
		$s3 = "";
	} else {
		$s3 = substr($string,$b+1);
	}
	$string = $s1.strrev($s2).$s3;
	
}

//algorithm is based on me drawing our the scenarios of swapping
//I couldn't come up with a generalized way to handle situations
//so I two, depending on whether you are moving the character to earlier or later in the string
function move(&$string,$a,$b){
	if($a > $b){
		//moving a letter to earlier in the string
		
		//get everything up to the position where we are moving the letter
		$s1 = substr($string,0,$b);
		//the letter we are inserting
		$s2 = $string[$a];
		//get everything from the position where we want to insert up to the position BEFORE where we are moving from
		$s3 = substr($string,$b,$a-$b);
		//get everything after the position we are moving from
		$s4 = substr($string,$a+1);
	} else {
		//get everything up to the position where are moving the letter from
		$s1 = substr($string,0,$a);
		//get everything after the position we are moving the letter from up to the position we are moving the letter to
		$s2 = substr($string,$a+1,$b-$a);
		//the letter being moved
		$s3 = $string[$a];
		//everything after the letter being moved
		$s4 = substr($string,$b+1);
	}
	$string = $s1.$s2.$s3.$s4;
}
	
	



