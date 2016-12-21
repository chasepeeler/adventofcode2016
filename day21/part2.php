<?php

$input = array_map("trim",file(__DIR__."/input.txt"));

$command_map = [];
$command_map['swapPos'] = '/swap position (\d+) with position (\d+)/';
$command_map['swapLetter'] = '/swap letter ([a-z]) with letter ([a-z])/';
$command_map['rotateLR'] = '/rotate (left|right) (\d+) step/';
$command_map['rotatePos'] = '/rotate based on position of (letter) ([a-z])/'; //capturing two items so we can have a uniform $a,$b parameter set at indexes 1 and 2
$command_map['reverse'] = '/reverse positions (\d+) through (\d+)/';
$command_map['move'] = '/move position (\d+) to position (\d+)/';

$shuffled = "fbgdceah";
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
$shuffled = "decab";
*/

//we need to run through our command in reverse
$input = array_reverse($input);



foreach($input as $command){
	foreach($command_map as $func=>$regex){
		if(preg_match($regex,$command,$m)){
			$func($shuffled,$m[1],$m[2]);
			continue 2; //no need to keep checking if it matches the other commands
		}
	}
	echo "Invalid command! {$command}".PHP_EOL;
	exit(1);
}

echo "Unshuffled password is ".$shuffled.PHP_EOL;

//this works exactly the same as before
//abcde.. if you swap 0 and 1 you get bacde. If you then swap 0 1 and you get abcde...
function swapPos(&$string,$a,$b){
	$temp = $string[$a];
	$string[$a] = $string[$b];
	$string[$b] = $temp;
}

//this works exactly the same as before, since it's a straight up swap letter for letter
function swapLetter(&$string,$a,$b){
	$string = str_replace($a,".",$string);
	$string = str_replace($b,$a,$string);
	$string = str_replace(".",$b,$string);
}

//this will use the same logic, but, we'll reverse the string before doing a left rotation (to unrotate it) instead
//of before doing a right rotation
function rotateLR(&$string,$dir,$steps){
	$newString = "";
	$steps = $steps%strlen($string); //a string of length 5 with a rotation of 6, is really a rotation of 1
	if($dir == 'left'){
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
	if($dir == 'left'){
		//undo our reverse
		$string = strrev($newString);
	} else {
		$string = $newString;	
	}
}

//this one is the most difficult, since you need the position of the letter BEFORE
//you rotate...
function rotatePos(&$string,$unused,$letter){
	$steps = 1;
	while(1){
		$copy = $string;
		
		rotateLR($copy,"right",$steps); //unrotate, since we reversed the rotate method, to unrotate a right rotation, we need to still say right
		
		//now we need to get the position of the letter pre rotation
		$pos = strpos($copy,$letter);
		
		//find the number of steps to rotate based on the position, same logic as before
		if($pos >= 4){
			$pos++;
		}
		$pos++;
		
		$copy2 = $copy;
		//redo the rotate using the calculated number of steps for the given position.
		rotateLR($copy2,"left",$pos); //since our method is actually an unrotate now, we need to say "left" for a right rotation.
		
		//if the rotation of the unrotation is the same as our starting string, then the unrotated version is
		//our string after reversing the command
		if($copy2 == $string){
			$string = $copy;
			break;
		}
		
		
		$steps++;
	}
}

//this works the same as before, since you're just reversing the reversal on the same substring
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

//the logic is the same as before, except to reverse a move 1 to 3, you would do a move 3 to 1.. so I swapped
//the input parameters.
function move(&$string,$b,$a){
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
	
	



