<?php


$instructions = array_map("trim",file(__DIR__."/input.txt"));

//only difference from part1 is c is initialized to 1
$registers = ['a'=>0,'b'=>0,'c'=>1,'d'=>0];

//keeps track of the current instruction
$pointer = 0;

//regexes to determine the instruction
$cpy = '/cpy (-?[a-d0-9]+) ([a-d])/';
$incdec = '/(inc|dec) ([a-d])/';
$jnz = '/jnz (-?[a-d0-9]+) (-?\d+)/';

$debug_steps = 0;
$last_debug_steps = 1;

//loop as long as we are pointed at a valid instruction
while(array_key_exists($pointer,$instructions)){
	
	$ins = $instructions[$pointer];
	
	if(preg_match($cpy,$ins,$m)){
		if(is_numeric($m[1])){
			//if the first parameter is a number, then that number is copied
			$from = $m[1];
		} else {
			//otherwise the value in the register specified by the first parameter is copied
			$from = $registers[$m[1]];
		}
		//it's copied into the register specified in the second parameter
		$registers[$m[2]] = $from;
		
		//go to the next instruction
		$pointer++;
	} elseif(preg_match($incdec,$ins,$m)){
		//if the command is inc, we increase by one, otherwise we decrease by 1
		$factor = $m[1] == "inc" ? 1 : -1;
		
		//update the value in the register specified in the 1st parameter (index 1 holds our instruction in this case)
		$registers[$m[2]] += $factor;
		
		//go to the next instruction
		$pointer++;
	} elseif(preg_match($jnz,$ins,$m)){
		if(is_numeric($m[1])){
			//if the first parameter is a number, we'll use that to determine if we should do the jump
			$test = $m[1];
		} else {
			//otherwise the value we should test is in the specified register
			$test = $registers[$m[1]];
		}
		
		//only jump if the test value is NOT zero.
		if($test != 0){
			//the amount we jump is relative to the current position, so, modify the pointer
			//by the amount specified in the 2nd parameter
			$pointer += $m[2];
		} else {
			//otherwise just go to the next instruction
			$pointer++;
		}
	} else {
		//just in case
		echo "Invalid Command: {$ins}".PHP_EOL;
		exit(1);
	}
	
	//was having issues, so I added the ability to run in "debug"
	//mode, where the program will break and print the current register
	//values after executing a specified number of instructions (1 by default)
	//
	//this doesn't exist in part 1, because I didn't need it there, and figured it was pointless
	//to "backport" it. You'll also notice no changes in the code from part1. That's because
	//nothing was broken, it just took a long time for the program to run.
	if($argv[1] == 'DEBUG' && $debug_steps == 0){
		echo $ins.PHP_EOL;
		printRegisters($registers);
		echo PHP_EOL;
		echo "Execute how many instructions? [{$last_debug_steps}] ";
		$debug_steps = trim(strtolower(fgets(STDIN)));
		if(!is_numeric($debug_steps)){
			$debug_steps = $last_debug_steps;
		}
		$last_debug_steps = $debug_steps;
	}
	$debug_steps--;
	
}

printRegisters($registers);

function printRegisters($registers){
	echo "Registers:".PHP_EOL;
	echo "----------".PHP_EOL;
	foreach($registers as $r=>$v){
		echo "{$r}: {$v}".PHP_EOL;
	}
}