<?php

$input = file_get_contents(__DIR__."/input.txt");

//output the size
echo decompress($input);


function parseSymbol($symbol){
	//gets the #of characters and how many times to repeat them from the "symbol"
	$regex = '/(\d+)x(\d+)/';
	preg_match($regex,$symbol,$m);
	return ["repeat"=>$m[2],"characters"=>$m[1]];
}


function decompress($input)
{
	//whether or not we're capturing our "symbol"
	$capturing_symbol = false;
	//holds the contents of the symbol
	$symbol_buffer = "";
	
	//size of the input
	$size = 0;
	
	//iterate through our string
	for($i=0;$i<strlen($input);$i++){
		//current character
		$char = $input[$i];
		
		//if we are in symbol capture mode
		if($capturing_symbol){
			//this means we're at the end of the symbol
			if($char == ")"){
				//turn off capturing mode
				$capturing_symbol = false;
				//parse our our symbol
				$p = parseSymbol($symbol_buffer);
				//get the substring we need to decompress according to the number of characters
				$subinput = substr($input,$i+1,$p['characters'])
				
				//whatever is returned will be repeated by the number specified in our symbol, and that will be added to our size
				$size += $p['repeat']*decompress($subinput);
				
				//we need to skip forward in our input since we just pulled out a bunch of characters to decompress
				$i += $p['characters'];
			} else {
				//otherwise just capture the next character in our symbol
				$symbol_buffer .= $char;
			}
		} else {
			//we aren't currently in capture mode
			if($char == "("){
				//we need to start capture mode
				$capturing_symbol = true;
				//clear out the buffer
				$symbol_buffer = "";
			} else {
				//just a character, so, increase the size by 1
				$size++;
			}
		}
	}
	return $size;
	
}
	
	
	
