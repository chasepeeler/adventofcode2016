<?php
//the following will brute force the solution
//but, it's crashing with a large number of elves... so, we'll use this to run simulations with small
//numbers until we determine the pattern, then write a script (part2)
//that will calculate the answer using the pattern we find

$num_elves = $argv[1];


class Elf {
	public $num;
	public $nextElf;
	public $prevElf;
	
	public function __construct($num,$nextElf=null,$prevElf=null){
		$this->num = $num;
	}
	
	public function __toString(){
		return "Elf {$this->num}";
	}
}

//create our first and last elf, and link them to each other
//last elf's next elf is the first elf. First elfs prev elf is the last elf
$lastElf = new Elf($num_elves);
$firstElf = new Elf(1);
$lastElf->nextElf = $firstElf;
$firstElf->prevElf = $lastElf;

$prevElf = $firstElf;
//create our other elves
for($i=2;$i<$num_elves;$i++){
	$elf = new Elf($i);
	
	$elf->prevElf = $prevElf;
	$prevElf->nextElf = $elf;
	$prevElf = $elf;
}
echo "foo";
$elf->nextElf = $lastElf;
$lastElf->prevElf = $elf;


$elf = $firstElf;

//while there is more than 1 elf
while($num_elves > 1){
	//find our partner across the circle
	$partner = getPartnerElf($elf,$num_elves);
	
	$newNext = $partner->nextElf;
	$newPrev = $partner->prevElf;
	
	//remove partner by linking its previous to its next, and vice versa
	$newPrev->nextElf = $newNext;
	$newNext->prevElf = $newPrev;
	
	$num_elves--;
	if($num_elves == 1){
		break;
	}
	
	$elf = $elf->nextElf;
}

echo "Elf {$elf->num} has all the presents.".PHP_EOL;


function getPartnerElf($elf,$num_elves){
	//our partner across the circle is going to be half the number of elves
	//in the cirle, rounded down
	$steps = intval($num_elves/2);
	while($steps > 0){
		$elf = $elf->nextElf;
		$steps--;
	}
	return $elf;
}




