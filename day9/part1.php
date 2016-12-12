<?php

$input = str_split(file_get_contents(__DIR__."/input.txt"));

$output = "";
$indicator_buffer = "";
$repeat_buffer = "";
$repeat_chars = 0;
$repeat_times = 0;
$capturing_indicator = false;
$capturing_repeat = false;
foreach($input as $letter){
	
	if($capturing_repeat){
		$repeat_buffer .= $letter;
		$repeat_chars--;
		if($repeat_chars == 0){
			$output .= str_repeat($repeat_buffer,$repeat_times);
			$capturing_repeat = false;
		}
		continue;
	}
	
	if(!$capturing_indicator && "(" == $letter){
		$capturing_indicator = true;
		$indicator_buffer = "";
		continue;
	}
	
	if($capturing_indicator && ")" == $letter){
		$capturing_repeat = true;
		list($repeat_chars,$repeat_times) = explode("x",$indicator_buffer);
		$repeat_buffer = "";
		$capturing_indicator = false;
		continue;
	}
	
	if($capturing_indicator){
		$indicator_buffer .= $letter;
		continue;
	}
	
	
	
	$output .= $letter;
	
}


echo "Total Length: ".strlen($output).PHP_EOL;