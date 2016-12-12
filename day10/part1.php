<?php

$input = array_map("trim",file(__DIR__."/input.txt"));

$bots = [];
$outputs = [];


$high_low_command = '/bot (\d+) gives low to (bot|output) (\d+) and high to (bot|output) (\d+)/';
$give_value_command = '/value (\d+) goes to bot (\d+)/';
$_input = [];

while(1){

	if(empty($input) && !empty($_input)){
		//start over with the commands still needed to process
		$input = $_input;
		$_input = [];
	} elseif(empty($input) && empty($_input)) {
		//nothing left to process
		break;
	}
	
	
	//get the next command
	$command = array_shift($input);

	if(preg_match($high_low_command,$command,$m)){
		//this is the bot giving away the chips
		$from_bot_index = intval($m[1]);
		
		//the bot hasn't been registered yet, so create it
		if(!array_key_exists($from_bot_index,$bots)){
			$bots[$from_bot_index] = ["low"=>null,"high"=>null];
		}
		$from_bot = $bots[$from_bot_index];
		$low = $from_bot["low"];
		$high = $from_bot["high"];
		
		//a bot can only give away chips if it has 2
		//so if either are null, save the command and go to the next command
		if($low == null || $high == null){
			$_input[] = $command;
			continue;
		}
		
		//these will be the bots (if any) that receive chips,
		//so that we can test if either of them has the chips in question after 
		//a possession change
		$bot1 = $bot2 = null;
		$result = false;
		
		if($m[2] == "output"){
			//put the chip into the proper output
			$result = put_in_output($outputs,$m[3],$from_bot["low"]);
			//we don't assign into to bot1, because no bot received this value
		} else {
			//give the chip to the proper bot
			$result = give_chip($bots,$m[3],$from_bot["low"]);
			//save the bot as bot1
			$bot1 = $bots[$m[3]];
		}
		//the chip transfer failed. That means it wasn't given to anyone,
		//so the current bot still has it.
		//save the command and go to the next one
		if(!$result){
			$_input[] = $command;
			continue;
		}
			
		$result = false;
		if($m[4] == "output"){
			//put the chip into the proper output
			$result = put_in_output($outputs,$m[5],$from_bot["high"]);
			//we don't assign into to bot2, because no bot received this value
		} else {
			//give the chip to the proper bot
			$result = give_chip($bots,$m[5],$from_bot["high"]);
			//save the bot as bot2
			$bot2 = $bots[$m[5]];
		}
		//the chip transfer failed. That means it wasn't given to anyone,
		//so the current bot still has it.
		//save the command and go to the next one
		if(!$result){
			$_input[] = $command;
			continue;
		}
		
		//we able to give away both chips, so now bot has none
		$bots[$from_bot_index] = ["low"=>null,"high"=>null];
		
		//bot 1 and/or bot 2 received a chip. So we'll check which chips they have now and see if it's the ones we're looking for.
		if($bot1 !== null && has_chips($bot1,61,17)){
			echo "Bot {$m[3]} has chips 17 and 61".PHP_EOL;
		} elseif($bot2 !== null && has_chips($bot2,61,17)){
			echo "Bot {$m[5]} has chips 17 and 61".PHP_EOL;
		}
		
	} elseif(preg_match($give_value_command,$command,$m)){
		//we're just giving a chip to the robot from the input
		
		if(!give_chip($bots,$m[2],$m[1])){
			//it failed, so, save command and continue.
			$_input[] = $command;
			continue;
		} else {
			//since the bot now has a new chip, see if it has the ones we're looking for
			if(has_chips($bots[$m[2]],61,17)){
				echo "Bot {$m[2]} has chips 61 and 17".PHP_EOL;	
			}
		}
	} else {
		echo "Invalid command: {$command}".PHP_EOL;
		continue;
	}
	
}

//added to compute answer for part2
echo $outputs[0]*$outputs[1]*$outputs[2].PHP_EOL;


function has_chips($bot,$c1,$c2){
	//make sure that we're checking the proper values
	if($c2 < $c1){
		$t = $c2;
		$c2 = $c1;
		$c1 = $t;
	}
	
	return $bot["low"] == $c1 && $bot["high"] == $c2;
}

function put_in_output(&$outputs,$output_num,$chip){
	//if the output isn't registered yet, do so.
	if(!array_key_exists($output_num,$outputs)){
		$outputs[$output_num] = null;
	}
	
	//put the chip in the output
	$outputs[$output_num] = $chip;
	return true;
}

function give_chip(&$bots,$bot_num,$chip){
	//if the bot doesn't exist yet, register it
	if(!array_key_exists($bot_num,$bots)){
		$bot = ["low"=>null,"high"=>null];
	} else {
		//get the existing bot
		$bot = $bots[$bot_num];
	}
	
	
	if($bot["low"] == null && $bot["high"] == null){
		//if the bot doesn't have any chips, but this one in the low slot
		$bot["low"] = $chip;
	} elseif($bot["low"] == null){
		//if the bot only has a high chip
		
		//and it's higher than the new chip, the new chip goes into the low slot
		if($bot["high"] >= $chip){
			$bot["low"] = $chip;
		} else {
			//otherwise the high chip is moved to the low slot
			$bot["low"] = $bot["high"];
			//and the new chip put in the high slot
			$bot["high"] = $chip;
		}
	} elseif($bot["high"] == null){
		//if the bot only has a low chip
		
		//and its lower than the new chip
		if($bot["low"] <= $chip){
			//the new chip goes into the high slot
			$bot["high"] = $chip;
		} else {
			//the current chip is moved to the high slot
			$bot["high"] = $bot["low"];
			//and the new chip is put in the low slot
			$bot["low"] = $chip;
		}
	} else {
		//bot already has two chips, so, don't take either one
		return false;
	}
	
	//save the updated bot
	$bots[$bot_num] = $bot;
	return true;
}

