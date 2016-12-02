<?php


//converted newlines to semicolons
$instructions = "LRRLLLRDRURUDLRDDURULRULLDLRRLRLDULUDDDDLLRRLDUUDULDRURRLDULRRULDLRDUDLRLLLULDUURRRRURURULURRULRURDLULURDRDURDRLRRUUDRULLLLLDRULDDLLRDLURRLDUURDLRLUDLDUDLURLRLDRLUDUULRRRUUULLRDURUDRUDRDRLLDLDDDLDLRRULDUUDULRUDDRLLURDDRLDDUDLLLLULRDDUDDUUULRULUULRLLDULUDLLLLURRLDLUDLDDLDRLRRDRDUDDDLLLLLRRLLRLUDLULLDLDDRRUDDRLRDDURRDULLLURLRDLRRLRDLDURLDDULLLDRRURDULUDUDLLLDDDLLRLDDDLLRRLLURUULULDDDUDULUUURRUUDLDULULDRDDLURURDLDLULDUDUDDDDD;RUURUDRDUULRDDLRLLLULLDDUDRDURDLRUULLLLUDUDRRUDUULRRUUDDURDDDLLLLRRUURULULLUDDLRDUDULRURRDRDLDLDUULUULUDDLUDRLULRUDRDDDLRRUUDRRLULUULDULDDLRLURDRLURRRRULDDRLDLLLRULLDURRLUDULDRDUDRLRLULRURDDRLUDLRURDDRDULUDLDLLLDRLRUDLLLLLDUDRDUURUDDUDLDLDUDLLDLRRDLULLURLDDUDDRDUDLDDUULDRLURRDLDLLUUDLDLURRLDRDDLLDLRLULUDRDLLLDRLRLLLDRUULUDLLURDLLUURUDURDDRDRDDUDDRRLLUULRRDRULRURRULLDDDUDULDDRULRLDURLUDULDLDDDLRULLULULUDLDDRDLRDRDLDULRRLRLRLLLLLDDDRDDULRDULRRLDLUDDDDLUDRLLDLURDLRDLDRDRDURRDUDULLLDLUDLDRLRRDDDRRLRLLULDRLRLLLLDUUURDLLULLUDDRLULRDLDLDURRRUURDUDRDLLLLLLDDDURLDULDRLLDUDRULRRDLDUDRLLUUUDULURRUR;URRRLRLLDDDRRLDLDLUDRDRDLDUDDDLDRRDRLDULRRDRRDUDRRUUDUUUDLLUURLRDRRURRRRUDRLLLLRRDULRDDRUDLRLUDURRLRLDDRRLUULURLURURUDRULDUUDLULUURRRDDLRDLUDRDLDDDLRUDURRLLRDDRDRLRLLRLRUUDRRLDLUDRURUULDUURDRUULDLLDRDLRDUUDLRLRRLUDRRUULRDDRDLDDULRRRURLRDDRLLLRDRLURDLDRUULDRRRLURURUUUULULRURULRLDDDDLULRLRULDUDDULRUULRRRRRLRLRUDDURLDRRDDULLUULLDLUDDDUURLRRLDULUUDDULDDUULLLRUDLLLRDDDLUUURLDUDRLLLDRRLDDLUDLLDLRRRLDDRUULULUURDDLUR;UULDRLUULURDRLDULURLUDULDRRDULULUDLLDURRRURDRLRLLRLDDLURRDLUUDLULRDULDRDLULULULDDLURULLULUDDRRULULULRDULRUURRRUDLRLURDRURDRRUDLDDUURDUUDLULDUDDLUUURURLRRDLULURDURRRURURDUURDRRURRDDULRULRRDRRDRUUUUULRLUUUDUUULLRRDRDULRDDULDRRULRLDLLULUUULUUDRDUUUDLLULDDRRDULUURRDUULLUUDRLLDUDLLLURURLUDDLRURRDRLDDURLDLLUURLDUURULLLRURURLULLLUURUUULLDLRDLUDDRRDDUUDLRURDDDRURUURURRRDLUDRLUULDUDLRUUDRLDRRDLDLDLRUDDDDRRDLDDDLLDLULLRUDDUDDDLDDUURLDUDLRDRURULDULULUDRRDLLRURDULDDRRDLUURUUULULRURDUUDLULLURUDDRLDDUDURRDURRUURLDLLDDUUDLLUURDRULLRRUUURRLLDRRDLURRURDULDDDDRDD;LLRUDRUUDUDLRDRDRRLRDRRUDRDURURRLDDDDLRDURDLRRUDRLLRDDUULRULURRRLRULDUURLRURLRLDUDLLDULULDUUURLRURUDDDDRDDLLURDLDRRUDRLDULLRULULLRURRLLURDLLLRRRRDRULRUDUDUDULUURUUURDDLDRDRUUURLDRULDUDULRLRLULLDURRRRURRRDRULULUDLULDDRLRRULLDURUDDUULRUUURDRRLULRRDLDUDURUUUUUURRUUULURDUUDLLUURDLULUDDLUUULLDURLDRRDDLRRRDRLLDRRLUDRLLLDRUULDUDRDDRDRRRLUDUDRRRLDRLRURDLRULRDUUDRRLLRLUUUUURRURLURDRRUURDRRLULUDULRLLURDLLULDDDLRDULLLUDRLURDDLRURLLRDRDULULDDRDDLDDRUUURDUUUDURRLRDUDLRRLRRRDUULDRDUDRLDLRULDL";

//$instructions = "ULL;RRDDD;LURDL;UUUUD";

$instructions = array_map("str_split",explode(";",$instructions));

//lets actually create our keypad
$keypad = [
	[null,null,1,null,null],
	[null,2,3,4,null],
	[5,6,7,8,9],
	[null,'A','B','C',null],
	[null,null,'D',null,null]
];

//however, now our array indexes don't map to x,y coordinates, but to y,x coordinates - so we modified step 1 below as well
$current = [2,0]; //position 5
$code = [];

foreach($instructions as $instruction){
	foreach($instruction as $move){
		echo "I'm now at ".$keypad[$current[0]][$current[1]]." ({$current[0]},{$current[1]})".PHP_EOL;
		echo "I need to move ".$move;
		//I'm doing the following logic in chunks just so things are easy to see an understand.
		//they could definitely be combined to form less if-statements
		
		//step 1, determine axis
		if($move == "U" || $move == "D"){
			$index = 0; //moving along Y-axis
		} elseif($move == "L" || $move == "R"){
			$index = 1; //moving along X-axis
		} else {
			continue; //bad instruction
		}
		
		//step 2, find out our new position
		$new = $current;
		if($move == "U" || $move == "L"){
			$new[$index]--;
		} elseif($move == "D" || $move == "R"){
			$new[$index]++;
		}
		if(null == $keypad[$new[0]][$new[1]]){
			//no button,
			echo " but I can't, so I'm staying at ".$keypad[$current[0]][$current[1]]." ({$current[0]},{$current[1]})".PHP_EOL;
			continue;
		}
		
		$current = $new;
		echo " ... and I'm now at ".$keypad[$current[0]][$current[1]]." ({$current[0]},{$current[1]})".PHP_EOL;
		
		
	}
	echo "Adding ".$keypad[$current[0]][$current[1]]." to code".PHP_EOL.PHP_EOL;
	
	$code[] = $keypad[$current[0]][$current[1]];
}

echo "The code is: ".implode("",$code);


function getNumberForCoord($coord){
	
	list($x,$y) = $coord;
	
	return (3*$y)+$x+1;
}
	