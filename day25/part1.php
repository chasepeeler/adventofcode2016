<?php

//we're going to brute force this one if possible.
//run for each possible initial value in a.
//if we output the same character twice in a row, then we know this isn't
//repeating the pattern 010101... forever.
//When we hit one that appears to actually be repeating the pattern for ever,
//then we'll stop the program and try and that number...

//regexes to determine the instruction
$cpy = '/cpy (-?[a-d0-9]+) ([a-d])/';
$incdec = '/(inc|dec) ([a-d])/';
$jnz = '/jnz (-?[a-d0-9]+) (-?[a-d0-9]+)/';
$tgl = '/tgl (-?[a-d0-9]+)/';
$out = '/out (-?[a-d0-9]+)/';

$instructions = array_map("trim",file(__DIR__."/input.txt"));


for($i=0;true;$i++){
	echo PHP_EOL."Initializing a to {$i}".PHP_EOL;
	$out_val = -1;
	$registers = ['a'=>$i,'b'=>0,'c'=>0,'d'=>0];

	//keeps track of the current instruction
	$pointer = 0;

	//loop as long as we are pointed at a valid instruction
	while(array_key_exists($pointer,$instructions)){
	//	printRegisters($registers);
		$ins = $instructions[$pointer];
//		echo $ins.PHP_EOL;
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
			if(is_numeric($m[2])){
				//if the second parameter is a number, we'll use that to determine how many positions to change
				$change = $m[2];
			} else {
				//otherwise the value in the specified register will be used
				$change = $registers[$m[2]];
			}
			
			//only jump if the test value is NOT zero.
			if($test != 0){
				//the amount we jump is relative to the current position, so, modify the pointer
				//by the amount specified in the 2nd parameter
				$pointer += $change;
			} else {
				//otherwise just go to the next instruction
				$pointer++;
			}
		} elseif(preg_match($tgl,$ins,$m)){
			$tgl_ins = $m[1];
			
			if(!is_numeric($tgl_ins)){
				$tgl_ins = $registers[$tgl_ins];
			}
			
			$tgl_pointer = $pointer + $tgl_ins;
			if(array_key_exists($tgl_pointer,$instructions)){
				$t = explode(" ",$instructions[$tgl_pointer]);
				if(count($t) == 2){
					$t[0] = $t[0] == 'inc' ? 'dec' : 'inc';
				} elseif(count($t) == 3){
					$t[0] = $t[0] == 'jnz' ? 'cpy' : 'jnz';
				}
				$instructions[$tgl_pointer] = implode(" ",$t);
			}
			$pointer++;
		} elseif(preg_match($out,$ins,$m)){
			$prev_out = $out_val;
			if(is_numeric($m[1])){
				$out_val = $m[1];
			} else {
				$out_val = $registers[$m[1]];
			}
			if($prev_out === $out_val){
				echo PHP_EOL."Repeated Characters".PHP_EOL;
				break;
			}
			echo $out_val;
			$pointer++;
		} else {
			//bad command, skip it
		}
	}
}

printRegisters($registers);

function printRegisters($registers){
	echo "Registers:".PHP_EOL;
	echo "----------".PHP_EOL;
	foreach($registers as $r=>$v){
		echo "{$r}: {$v}".PHP_EOL;
	}
}